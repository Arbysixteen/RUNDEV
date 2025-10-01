<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pendaftaran', function () {
    return view('registration-simple');
});

// Email verification route
Route::get('/verify-email/{token}', [App\Http\Controllers\EmailVerificationController::class, 'verify'])->name('verify.email');

// Debug route
Route::get('/debug/session', function () {
    return response()->json([
        'session_data' => session()->all(),
        'session_id' => session()->getId(),
        'participant_logged_in' => session('participant_logged_in'),
        'participant_uid' => session('participant_uid'),
        'csrf_token' => csrf_token(),
        '_token' => session()->token()
    ]);
});

// Test CSRF route
Route::get('/csrf-test', function () {
    return view('csrf-test');
});

Route::post('/debug/csrf-test', function () {
    return redirect('/csrf-test')->with('success', 'CSRF test passed successfully!');
});

// Debug login route without CSRF for testing
Route::post('/debug/login-test', function (Illuminate\Http\Request $request) {
    \Log::info('Debug login test', [
        'email' => $request->email,
        'password' => $request->password ? 'provided' : 'missing',
        'session_id' => session()->getId(),
        'csrf_token' => $request->input('_token'),
        'session_token' => session()->token()
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Debug login test completed',
        'session_id' => session()->getId(),
        'csrf_match' => $request->input('_token') === session()->token()
    ]);
});

// Simple test route with no middleware
Route::post('/simple-test', function () {
    return response()->json(['success' => true, 'message' => 'Simple test works']);
});

// Alternative login route for testing
Route::post('/alt-login', [App\Http\Controllers\FirebaseAuthController::class, 'loginWeb']);

// Test dashboard without middleware
Route::get('/test/dashboard', function () {
    // Simulate logged in user
    $participantData = [
        'uid' => '8HoS8dkmBfZugqjACoZHBbGVcB03',
        'namaLengkap' => 'testarby123',
        'email' => 'testarby123@gmail.com',
        'idPeserta' => 'RD1758701978',
        'registrationDate' => '2025-09-24T08:19:38+00:00',
        'kategoriLomba' => 'N/A',
        'nomorWA' => 'N/A',
        'ukuranBaju' => 'N/A',
        'pembayaran' => 'belum'
    ];
    
    return view('participant.dashboard', compact('participantData'));
});

// Participant routes
Route::prefix('peserta')->group(function () {
    Route::get('/login', [App\Http\Controllers\ParticipantController::class, 'showLoginForm'])->name('participant.login');
    Route::post('/login', [App\Http\Controllers\FirebaseAuthController::class, 'loginWeb'])->name('participant.login.post');
    
    // Protected routes (require login)
    Route::middleware('participant.auth')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\ParticipantController::class, 'dashboard'])->name('participant.dashboard');
        Route::post('/logout', [App\Http\Controllers\ParticipantController::class, 'logout'])->name('participant.logout');
    });
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [App\Http\Controllers\AdminController::class, 'authenticate'])->name('admin.authenticate');
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::put('/participants/{id}', [App\Http\Controllers\AdminController::class, 'updateParticipant'])->name('admin.participants.update');
    Route::delete('/participants/{id}', [App\Http\Controllers\AdminController::class, 'deleteParticipant'])->name('admin.participants.delete');
    Route::post('/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
});
