<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class FirebaseAuthController extends Controller
{
    protected $auth;
    protected $database;

    public function __construct(Auth $auth, Database $database)
    {
        $this->auth = $auth;
        $this->database = $database;
    }

    public function register(Request $request)
    {
        // Log incoming request for debugging
        \Log::info('Firebase Auth Registration request received', [
            'request_data' => $request->all(),
            'headers' => $request->header(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip()
        ]);
        
        // Validate input
        $validator = Validator::make($request->all(), [
            'namaLengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'kategoriLomba' => 'required|string|in:5K,10K,Half Marathon',
            'nomorWA' => 'required|string|min:10',
            'ukuranBaju' => 'required|string|in:S,M,L,XL,XXL',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create user in Firebase Auth
            $userProperties = [
                'email' => $request->email,
                'password' => $request->password,
                'displayName' => $request->namaLengkap,
            ];

            $createdUser = $this->auth->createUser($userProperties);
            $uid = $createdUser->uid;

            // Generate participant ID
            $participantId = 'RD' . time();

            // Prepare data for Realtime Database (without password)
            $participantData = [
                'uid' => $uid,
                'namaLengkap' => $request->namaLengkap,
                'email' => $request->email,
                'kategoriLomba' => $request->kategoriLomba,
                'nomorWA' => $request->nomorWA,
                'ukuranBaju' => $request->ukuranBaju,
                'idPeserta' => $participantId,
                'registrationDate' => date('c'), // ISO 8601
                'pembayaran' => 'belum',
                'emailVerified' => false
            ];

            // Save to Firebase Realtime Database
            $this->database->getReference('participants/' . $uid)->set($participantData);

            // NOTE: Email verification will be sent by client-side Firebase (registration-simple.blade.php)
            // No need for server-side email sending or SMTP configuration!

            // Log success
            \Log::info('Successfully created Firebase Auth user and saved participant data', [
                'uid' => $uid,
                'participant_id' => $participantId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi.',
                'data' => [
                    'participantId' => $participantId,
                    'uid' => $uid,
                    'emailSent' => true
                ]
            ]);

        } catch (EmailExists $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar. Silakan gunakan email lain atau login ke halaman peserta.'
            ], 422);
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error creating Firebase Auth user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendaftar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify password with Firebase Auth
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $user = $signInResult->data();
            $uid = $user['localId'];

            // Get user data from Firebase Auth
            $firebaseUser = $this->auth->getUser($uid);
            
            // Check if email is verified
            if (!$firebaseUser->emailVerified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email belum diverifikasi. Silakan cek inbox email Anda untuk link verifikasi.',
                    'code' => 'EMAIL_NOT_VERIFIED'
                ], 403);
            }
            
            // Try to get additional participant data from Realtime Database
            $participantSnapshot = $this->database->getReference('participants/' . $uid)->getSnapshot();
            
            if ($participantSnapshot->exists()) {
                // Use data from Realtime Database if exists
                $participantData = $participantSnapshot->getValue();
            } else {
                // Fallback to Firebase Auth data
                $participantData = [
                    'uid' => $uid,
                    'namaLengkap' => $firebaseUser->displayName ?? 'User',
                    'email' => $firebaseUser->email,
                    'idPeserta' => 'RD' . substr($uid, 0, 8), // Generate from UID
                    'registrationDate' => $firebaseUser->metadata->createdAt->format('c'),
                    'kategoriLomba' => 'N/A',
                    'nomorWA' => 'N/A',
                    'ukuranBaju' => 'N/A',
                    'pembayaran' => 'belum'
                ];
            }

            // Create session
            session([
                'participant_logged_in' => true,
                'participant_uid' => $uid,
                'participant_id' => $participantData['idPeserta'],
                'participant_email' => $participantData['email'],
                'participant_name' => $participantData['namaLengkap'],
                'firebase_token' => $signInResult->idToken()
            ]);

            // Force session save
            session()->save();

            // Log session for debugging
            \Log::info('Session created for participant login', [
                'session_data' => session()->all(),
                'participant_uid' => $uid,
                'participant_id' => $participantData['idPeserta']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => '/peserta/dashboard'
            ]);

        } catch (UserNotFound $e) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak ditemukan. Silakan daftar terlebih dahulu.',
                'code' => 'USER_NOT_FOUND'
            ], 404);
        } catch (InvalidPassword $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah. Silakan periksa kembali password Anda.',
                'code' => 'INVALID_PASSWORD'
            ], 401);
        } catch (\Exception $e) {
            \Log::error('Firebase Auth login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function loginWithPhone(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'nomorWA' => 'required|string|min:10',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find participant by phone number in Realtime Database
            $participants = $this->database->getReference('participants')
                ->orderByChild('nomorWA')
                ->equalTo($request->nomorWA)
                ->getSnapshot();

            if (!$participants->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor WhatsApp tidak ditemukan. Silakan daftar terlebih dahulu.'
                ], 404);
            }

            // Get participant data
            $participantData = null;
            $uid = null;
            
            foreach ($participants->getValue() as $key => $participant) {
                $participantData = $participant;
                $uid = $key; // UID is the key in Firebase
                break;
            }

            // Try to sign in with email and password using Firebase Auth
            $signInResult = $this->auth->signInWithEmailAndPassword($participantData['email'], $request->password);
            
            // Get updated user data from Firebase Auth
            $firebaseUser = $this->auth->getUser($uid);
            
            // Create session
            session([
                'participant_logged_in' => true,
                'participant_uid' => $uid,
                'participant_id' => $participantData['idPeserta'],
                'participant_email' => $participantData['email'],
                'participant_name' => $participantData['namaLengkap'],
                'firebase_token' => $signInResult->idToken()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => '/peserta/dashboard'
            ]);

        } catch (InvalidPassword $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah.'
            ], 401);
        } catch (\Exception $e) {
            \Log::error('Firebase Auth phone login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Revoke Firebase token if exists
            if (session('firebase_token')) {
                // Optional: Revoke the token on Firebase side
                // $this->auth->revokeRefreshTokens(session('participant_uid'));
            }
            
            session()->flush();
            
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
                'redirect' => '/peserta/login'
            ]);
        } catch (\Exception $e) {
            \Log::error('Firebase Auth logout error', [
                'error' => $e->getMessage()
            ]);
            
            session()->flush(); // Force flush session even if Firebase fails
            
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
                'redirect' => '/peserta/login'
            ]);
        }
    }

    // Web login method (non-AJAX) for better session handling
    public function loginWeb(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Data yang dimasukkan tidak valid.');
        }

        try {
            // Verify password with Firebase Auth
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $user = $signInResult->data();
            $uid = $user['localId'];

            // Get user data from Firebase Auth
            $firebaseUser = $this->auth->getUser($uid);
            
            // Check if email is verified
            if (!$firebaseUser->emailVerified) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Email belum diverifikasi. Silakan cek inbox email Anda untuk link verifikasi.')
                    ->with('email_not_verified', true)
                    ->with('user_email', $request->email);
            }
            
            // Try to get additional participant data from Realtime Database
            $participantSnapshot = $this->database->getReference('participants/' . $uid)->getSnapshot();
            
            if ($participantSnapshot->exists()) {
                // Use data from Realtime Database if exists
                $participantData = $participantSnapshot->getValue();
            } else {
                // Fallback to Firebase Auth data
                $participantData = [
                    'uid' => $uid,
                    'namaLengkap' => $firebaseUser->displayName ?? 'User',
                    'email' => $firebaseUser->email,
                    'idPeserta' => 'RD' . substr($uid, 0, 8), // Generate from UID
                    'registrationDate' => $firebaseUser->metadata->createdAt->format('c'),
                    'kategoriLomba' => 'N/A',
                    'nomorWA' => 'N/A',
                    'ukuranBaju' => 'N/A',
                    'pembayaran' => 'belum'
                ];
            }

            // Create session
            session([
                'participant_logged_in' => true,
                'participant_uid' => $uid,
                'participant_id' => $participantData['idPeserta'],
                'participant_email' => $participantData['email'],
                'participant_name' => $participantData['namaLengkap'],
                'firebase_token' => $signInResult->idToken()
            ]);

            // Force session save
            session()->save();

            // Log session for debugging
            \Log::info('Web login session created', [
                'session_data' => session()->all(),
                'participant_uid' => $uid,
                'participant_id' => $participantData['idPeserta']
            ]);

            return redirect('/peserta/dashboard')->with('success', 'Login berhasil!');

        } catch (UserNotFound $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akun tidak ditemukan. Silakan daftar terlebih dahulu.');
        } catch (InvalidPassword $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Password salah. Silakan periksa kembali password Anda.');
        } catch (\Exception $e) {
            \Log::error('Firebase Auth web login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat login. Silakan coba lagi.');
        }
    }

    // Debug method to list all Firebase Auth users
    public function listUsers()
    {
        try {
            $users = $this->auth->listUsers();
            $userList = [];
            
            foreach ($users as $user) {
                $userList[] = [
                    'uid' => $user->uid,
                    'email' => $user->email,
                    'displayName' => $user->displayName,
                    'emailVerified' => $user->emailVerified,
                    'disabled' => $user->disabled,
                    'createdAt' => $user->metadata->createdAt->format('Y-m-d H:i:s'),
                ];
            }
            
            return response()->json([
                'success' => true,
                'users' => $userList,
                'total' => count($userList)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error listing users',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
