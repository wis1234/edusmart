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
        // Check if user is participant
        if (!$videoCall->hasParticipant(Auth::user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $videoCall->messages()
            ->with('user:id,name,profile_photo_path')
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        return response()->json($messages);
    }

    /**
     * Store a new message
     */
    public function store(Request $request, VideoCall $videoCall): JsonResponse
    {
        // Check if user is participant
        if (!$videoCall->hasParticipant(Auth::user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
            'type' => 'sometimes|string|in:text,file,image,system',
            'metadata' => 'sometimes|array',
        ]);

        $message = $videoCall->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'type' => $request->type ?? 'text',
            'metadata' => $request->metadata,
        ]);

        $message->load('user:id,name,profile_photo_path');

        // Broadcast to other participants
        broadcast(new \App\Events\VideoCallMessageSent($videoCall, $message))->toOthers();

        return response()->json($message, 201);
    }

    /**
     * Get activities for a video call
     */
    public function activities(VideoCall $videoCall): JsonResponse
    {
        // Check if user is participant
        if (!$videoCall->hasParticipant(Auth::user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $activities = $videoCall->activities()
            ->with('user:id,name,profile_photo_path')
            ->latest()
            ->take(100)
            ->get();

        return response()->json($activities);
    }

    /**
     * Record an activity
     */
    public function recordActivity(Request $request, VideoCall $videoCall): JsonResponse
    {
        // Check if user is participant
        if (!$videoCall->hasParticipant(Auth::user())) {
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

        $activity->load('user:id,name,profile_photo_path');

        // Broadcast to other participants
        broadcast(new \App\Events\VideoCallActivityRecorded($videoCall, $activity))->toOthers();

        return response()->json($activity, 201);
    }
} 