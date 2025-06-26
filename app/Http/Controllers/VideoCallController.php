<?php

namespace App\Http\Controllers;

use App\Models\VideoCall;
use App\Models\VideoCallParticipant;
use App\Models\User;
use App\Models\School;
use App\Models\Notification;
use App\Events\NewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class VideoCallController extends Controller
{
    /**
     * Display a listing of video calls
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = VideoCall::with(['initiator', 'school', 'participants.user']);

        // Filter based on user role
        if ($user->isAdmin()) {
            // Admin sees all calls
        } elseif ($user->isSchoolAdmin()) {
            // School admin sees calls from their school
            $query->where('school_id', $user->school_id);
        } else {
            // Regular users see calls they initiated or participated in
            $query->where(function ($q) use ($user) {
                $q->where('initiator_id', $user->id)
                  ->orWhereHas('participants', function ($subQ) use ($user) {
                      $subQ->where('user_id', $user->id);
                  });
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $videoCalls = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('video_calls.index', [
            'videoCalls' => $videoCalls,
            'filters' => $request->only(['status', 'type']),
        ]);
    }

    /**
     * Display history of video calls
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $query = VideoCall::with(['initiator', 'school', 'participants.user']);

        // Filter based on user role
        if ($user->isAdmin()) {
            // Admin sees all calls
        } elseif ($user->isSchoolAdmin()) {
            // School admin sees calls from their school
            $query->where('school_id', $user->school_id);
        } else {
            // Regular users see calls they initiated or participated in
            $query->where(function ($q) use ($user) {
                $q->where('initiator_id', $user->id)
                  ->orWhereHas('participants', function ($subQ) use ($user) {
                      $subQ->where('user_id', $user->id);
                  });
            });
        }

        // Show only ended or cancelled calls for history
        $query->whereIn('status', ['ended', 'cancelled']);

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $videoCalls = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('video_calls.history', [
            'videoCalls' => $videoCalls,
            'filters' => $request->only(['type']),
        ]);
    }

    /**
     * Show the form for creating a new video call
     */
    public function create()
    {
        $user = Auth::user();
        $users = collect();

        // Get available users based on role
        if ($user->isAdmin()) {
            $users = User::where('id', '!=', $user->id)->get();
        } elseif ($user->isSchoolAdmin()) {
            $users = User::where('school_id', $user->school_id)
                        ->where('id', '!=', $user->id)
                        ->get();
        } elseif ($user->isTeacher()) {
            // Teachers can call their students' parents and other teachers in their school
            $users = User::where('school_id', $user->school_id)
                        ->where('id', '!=', $user->id)
                        ->where(function ($q) {
                            $q->whereHas('roles', function ($roleQ) {
                                $roleQ->whereIn('name', ['parent', 'enseignant']);
                            });
                        })
                        ->get();
        } elseif ($user->isParent()) {
            // Parents can call teachers and other parents in their school
            $users = User::where('school_id', $user->school_id)
                        ->where('id', '!=', $user->id)
                        ->where(function ($q) {
                            $q->whereHas('roles', function ($roleQ) {
                                $roleQ->whereIn('name', ['parent', 'enseignant']);
                            });
                        })
                        ->get();
        }

        return view('video_calls.create', [
            'availableUsers' => $users,
            'schools' => $user->isAdmin() ? School::all() : collect([$user->school]),
        ]);
    }

    /**
     * Store a newly created video call
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:video,audio,both',
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'exists:users,id',
            'school_id' => $user->isAdmin() ? 'nullable|exists:schools,id' : 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Create the video call
            $videoCall = VideoCall::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'initiator_id' => $user->id,
                'school_id' => $request->school_id ?? $user->school_id,
                'status' => 'pending',
            ]);

            // Add initiator as host
            VideoCallParticipant::create([
                'video_call_id' => $videoCall->id,
                'user_id' => $user->id,
                'role' => 'host',
                'status' => 'joined',
                'joined_at' => now(),
            ]);

            // Add participants
            foreach ($request->participant_ids as $participantId) {
                VideoCallParticipant::create([
                    'video_call_id' => $videoCall->id,
                    'user_id' => $participantId,
                    'role' => 'participant',
                    'status' => 'invited',
                ]);

                // Send notification to participant
                $participant = User::find($participantId);
                $notification = Notification::create([
                    'user_id' => $participantId,
                    'type' => 'video_call_invitation',
                    'title' => 'Invitation à un appel',
                    'message' => "{$user->name} vous invite à un appel " . ($request->type === 'video' ? 'vidéo' : ($request->type === 'audio' ? 'audio' : 'vidéo/audio')),
                    'link' => route('video-calls.join', $videoCall->room_id),
                    'school_id' => $participant->school_id,
                ]);

                // Broadcast notification
                broadcast(new NewNotification($notification))->toOthers();
            }

            DB::commit();

            return redirect()->route('video-calls.show', $videoCall)
                           ->with('success', 'Call created successfully. Participants have been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de la création de l\'appel: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified video call
     */
    public function show(VideoCall $videoCall)
    {
        $videoCall->load(['initiator', 'school', 'participants.user']);

        $user = Auth::user();

        // Check if user can access this call
        if (!$videoCall->hasParticipant($user) && $videoCall->initiator_id !== $user->id) {
            abort(403, 'You do not have access to this call.');
        }

        // Get participant info for current user
        $participant = $videoCall->participants()->where('user_id', $user->id)->first();

        return view('video_calls.show', [
            'videoCall' => $videoCall,
            'currentParticipant' => $participant,
            'isHost' => $videoCall->isHost($user),
            'signalServerUrl' => env('SIGNAL_SERVER_URL', 'http://localhost:3001'),
        ]);
    }

    /**
     * Join a video call
     */
    public function join($roomId)
    {
        $videoCall = VideoCall::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();

        // Check if user is invited
        $participant = $videoCall->participants()->where('user_id', $user->id)->first();
        
        if (!$participant) {
            abort(403, 'Vous n\'êtes pas invité à cet appel.');
        }

        // Update participant status
        $participant->join();

        // Start the call if it's still pending
        if ($videoCall->isPending()) {
            $videoCall->start();
        }

        return redirect()->route('video-calls.show', $videoCall)
                       ->with('success', 'You have joined the call.');
    }

    /**
     * Leave a video call
     */
    public function leave($roomId)
    {
        $videoCall = VideoCall::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();

        $participant = $videoCall->participants()->where('user_id', $user->id)->first();
        
        if ($participant) {
            $participant->leave();
        }

        // If host leaves, end the call
        if ($videoCall->isHost($user)) {
            $videoCall->end();
        }

        return redirect()->route('video-calls.index')
                       ->with('success', 'Vous avez quitté l\'appel.');
    }

    /**
     * Decline a video call invitation
     */
    public function decline($roomId)
    {
        $videoCall = VideoCall::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();

        $participant = $videoCall->participants()->where('user_id', $user->id)->first();
        
        if ($participant) {
            $participant->decline();
        }

        return redirect()->route('video-calls.index')
                       ->with('success', 'Vous avez décliné l\'invitation.');
    }

    /**
     * End a video call (host only)
     */
    public function end($roomId)
    {
        $videoCall = VideoCall::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();

        if (!$videoCall->isHost($user)) {
            abort(403, 'Seul l\'hôte peut terminer l\'appel.');
        }

        $videoCall->end();

        return redirect()->route('video-calls.index')
                       ->with('success', 'L\'appel a été terminé.');
    }

    /**
     * Cancel a video call (host only)
     */
    public function cancel($roomId)
    {
        $videoCall = VideoCall::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();

        if (!$videoCall->isHost($user)) {
            abort(403, 'Seul l\'hôte peut annuler l\'appel.');
        }

        $videoCall->cancel();

        return redirect()->route('video-calls.index')
                       ->with('success', 'L\'appel a été annulé.');
    }

    /**
     * Verify access to a video call room (API)
     */
    public function verifyAccess($roomId)
    {
        $user = Auth::user();
        $videoCall = VideoCall::where('room_id', $roomId)->first();

        if (!$videoCall) {
            return response()->json(['canAccess' => false, 'message' => 'Room not found']);
        }

        $canAccess = $videoCall->hasParticipant($user) || $videoCall->initiator_id === $user->id;

        return response()->json([
            'canAccess' => $canAccess,
            'message' => $canAccess ? 'Access granted' : 'Access denied'
        ]);
    }

    /**
     * Get participants for a video call (API)
     */
    public function getParticipants($roomId)
    {
        $user = Auth::user();
        $videoCall = VideoCall::where('room_id', $roomId)->first();

        if (!$videoCall) {
            return response()->json(['participants' => []]);
        }

        // Check access
        if (!$videoCall->hasParticipant($user) && $videoCall->initiator_id !== $user->id) {
            return response()->json(['participants' => []]);
        }

        $participants = $videoCall->participants()
                                 ->with('user')
                                 ->get()
                                 ->map(function ($participant) {
                                     return [
                                         'id' => $participant->id,
                                         'user' => [
                                             'id' => $participant->user->id,
                                             'name' => $participant->user->name,
                                             'profile_photo' => $participant->user->profile_photo,
                                         ],
                                         'role' => $participant->role,
                                         'status' => $participant->status,
                                         'is_muted' => $participant->is_muted,
                                         'is_video_off' => $participant->is_video_off,
                                     ];
                                 });

        return response()->json(['participants' => $participants]);
    }

    /**
     * Get active participants for a call (AJAX)
     */
    public function getActiveParticipants($roomId)
    {
        $videoCall = VideoCall::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();

        // Check if user can access this call
        if (!$videoCall->hasParticipant($user) && $videoCall->initiator_id !== $user->id) {
            abort(403);
        }

        $participants = $videoCall->activeParticipants()
                                 ->with('user')
                                 ->get()
                                 ->map(function ($participant) {
                                     return [
                                         'id' => $participant->id,
                                         'user' => [
                                             'id' => $participant->user->id,
                                             'name' => $participant->user->name,
                                             'profile_photo' => $participant->user->profile_photo,
                                         ],
                                         'role' => $participant->role,
                                         'is_muted' => $participant->is_muted,
                                         'is_video_off' => $participant->is_video_off,
                                         'joined_at' => $participant->joined_at,
                                     ];
                                 });

        return response()->json($participants);
    }

    /**
     * Update participant status (mute/unmute, video on/off)
     */
    public function updateParticipantStatus(Request $request, $roomId)
    {
        $videoCall = VideoCall::where('room_id', $roomId)->firstOrFail();
        $user = Auth::user();

        $participant = $videoCall->participants()->where('user_id', $user->id)->first();
        
        if (!$participant) {
            abort(403);
        }

        if ($request->has('is_muted')) {
            $participant->update(['is_muted' => $request->is_muted]);
        }

        if ($request->has('is_video_off')) {
            $participant->update(['is_video_off' => $request->is_video_off]);
        }

        return response()->json(['success' => true]);
    }
} 