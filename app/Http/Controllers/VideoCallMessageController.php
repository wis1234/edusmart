<?php

namespace App\Http\Controllers;

use App\Models\VideoCall;
use App\Models\VideoCallMessage;
use App\Models\VideoCallActivity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VideoCallMessageController extends Controller
{
    /**
     * Get messages for a video call
     */
    public function index(VideoCall $videoCall): JsonResponse
    {
        // Check if user can access this call (participant or initiator)
        $user = Auth::user();
        if (!$videoCall->hasParticipant($user) && $videoCall->initiator_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $videoCall->messages()
            ->with('user:id,first_name,last_name,profile_photo')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->map(function ($message) {
                if ($message->user) {
                    $message->user->profile_photo = $message->user->profile_photo_url;
                }
                return $message;
            });

        return response()->json($messages);
    }

    /**
     * Store a new message
     */
    public function store(Request $request, VideoCall $videoCall): JsonResponse
    {
        // Check if user can access this call (participant or initiator)
        $user = Auth::user();
        if (!$videoCall->hasParticipant($user) && $videoCall->initiator_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
            'type' => 'sometimes|string|in:text,file,image,system',
            'metadata' => 'sometimes|array',
        ]);

        try {
            $message = $videoCall->messages()->create([
                'user_id' => Auth::id(),
                'message' => $request->message,
                'type' => $request->type ?? 'text',
                'metadata' => $request->metadata ?? [],
            ]);

            $message->load('user:id,first_name,last_name,profile_photo');
            
            // Ajouter l'URL complète de la photo de profil
            if ($message->user) {
                $message->user->profile_photo = $message->user->profile_photo_url;
            }

            // Broadcast to other participants if the event exists
            if (class_exists('\App\Events\VideoCallMessageSent')) {
                broadcast(new \App\Events\VideoCallMessageSent($videoCall, $message))->toOthers();
            }

            return response()->json($message, 201);
        } catch (\Exception $e) {
            \Log::error('Error saving video call message: ' . $e->getMessage(), [
                'video_call_id' => $videoCall->id,
                'user_id' => Auth::id(),
                'message' => $request->message
            ]);
            
            return response()->json([
                'error' => 'Failed to save message',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get activities for a video call
     */
    public function activities(VideoCall $videoCall): JsonResponse
    {
        // Check if user can access this call (participant or initiator)
        $user = Auth::user();
        if (!$videoCall->hasParticipant($user) && $videoCall->initiator_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $activities = $videoCall->activities()
            ->with('user:id,first_name,last_name,profile_photo')
            ->latest()
            ->take(100)
            ->get()
            ->map(function ($activity) {
                if ($activity->user) {
                    $activity->user->profile_photo = $activity->user->profile_photo_url;
                }
                return $activity;
            });

        return response()->json($activities);
    }

    /**
     * Record an activity
     */
    public function recordActivity(Request $request, VideoCall $videoCall): JsonResponse
    {
        // Check if user can access this call (participant or initiator)
        $user = Auth::user();
        if (!$videoCall->hasParticipant($user) && $videoCall->initiator_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'action' => 'required|string|max:50',
            'metadata' => 'sometimes|array',
        ]);

        $activity = $videoCall->activities()->create([
            'user_id' => Auth::id(),
            'action' => $request->action,
            'metadata' => $request->metadata,
        ]);

        $activity->load('user:id,first_name,last_name,profile_photo');
        
        // Ajouter l'URL complète de la photo de profil
        if ($activity->user) {
            $activity->user->profile_photo = $activity->user->profile_photo_url;
        }

        // Broadcast to other participants
        broadcast(new \App\Events\VideoCallActivityRecorded($videoCall, $activity))->toOthers();

        return response()->json($activity, 201);
    }
} 