<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoCallController;

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
    return $request->user();
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