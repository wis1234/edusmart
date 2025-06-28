<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoCallController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    // Retourner les données dans le format attendu par le serveur de signalisation
    return response()->json([
        'id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'profile_photo' => $user->profile_photo_url, // Utilise l'accesseur pour l'URL complète
        'email' => $user->email,
        'role' => $user->role,
        'school_id' => $user->school_id,
        'status' => $user->status,
        'validated' => $user->validated,
        'profile_locked' => $user->profile_locked,
        'two_factor_enabled' => $user->two_factor_enabled,
        'email_verified_at' => $user->email_verified_at,
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at
    ]);
});

// Route pour obtenir le token d'authentification pour le serveur de signalisation
Route::middleware('auth:sanctum')->get('/auth-token', function (Request $request) {
    return response()->json([
        'token' => $request->user()->createToken('video-call')->plainTextToken
    ]);
});

// Routes pour le serveur de signalisation WebRTC
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/video-calls/{roomId}/verify-access', [VideoCallController::class, 'verifyAccess']);
    Route::get('/video-calls/{roomId}/participants', [VideoCallController::class, 'getParticipants']);
}); 