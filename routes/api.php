<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;
use Illuminate\Support\Facades\Log;

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

// CSRF token route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Debug log request
Route::any('/debug-request', function (Request $request) {
    Log::info('Debug API request received', [
        'method' => $request->method(),
        'url' => $request->fullUrl(),
        'headers' => $request->header(),
        'data' => $request->all()
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Request logged',
        'data' => $request->all()
    ]);
});

// Firebase Auth Registration routes
Route::post('/register', [App\Http\Controllers\FirebaseAuthController::class, 'register']);
Route::post('/update-payment', [FirebaseController::class, 'updatePayment']);

// Email verification routes
Route::post('/resend-verification', [App\Http\Controllers\EmailVerificationController::class, 'resend']);

// Firebase Auth Login routes
Route::post('/participant/login', [App\Http\Controllers\FirebaseAuthController::class, 'login']);
Route::post('/participant/login-phone', [App\Http\Controllers\FirebaseAuthController::class, 'loginWithPhone']);
Route::post('/participant/logout', [App\Http\Controllers\FirebaseAuthController::class, 'logout']);

// Test route
Route::get('/test-firebase', [FirebaseController::class, 'testConnection']);

// Debug routes
Route::get('/debug/users', [App\Http\Controllers\FirebaseAuthController::class, 'listUsers']);
