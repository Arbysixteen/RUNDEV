<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Contract\Database;

class ParticipantController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function showLoginForm()
    {
        return view('participant.login');
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
            // Find participant by email
            $participants = $this->database->getReference('participants')
                ->orderByChild('email')
                ->equalTo($request->email)
                ->getSnapshot();

            if (!$participants->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak ditemukan. Silakan daftar terlebih dahulu.'
                ], 404);
            }

            // Get participant data
            $participantData = null;
            $firebaseId = null;
            
            foreach ($participants->getValue() as $key => $participant) {
                $participantData = $participant;
                $firebaseId = $key;
                break; // Take the first (should be only one)
            }

            // Check password
            if (!Hash::check($request->password, $participantData['password'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah.'
                ], 401);
            }

            // Create session
            session([
                'participant_logged_in' => true,
                'participant_id' => $participantData['idPeserta'],
                'participant_firebase_id' => $firebaseId,
                'participant_email' => $participantData['email'],
                'participant_name' => $participantData['namaLengkap']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => '/peserta/dashboard'
            ]);

        } catch (\Exception $e) {
            \Log::error('Participant login error', [
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

    public function dashboard()
    {
        // Log session data for debugging
        \Log::info('Dashboard access attempt', [
            'session_data' => session()->all(),
            'participant_logged_in' => session('participant_logged_in'),
            'participant_uid' => session('participant_uid')
        ]);

        // Check if participant is logged in
        if (!session('participant_logged_in')) {
            \Log::warning('Dashboard access denied - not logged in');
            return redirect('/peserta/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            // Get participant data from Firebase using UID
            $uid = session('participant_uid');
            
            if (!$uid) {
                \Log::warning('Dashboard access denied - no UID in session');
                session()->flush();
                return redirect('/peserta/login')->with('error', 'Session tidak valid.');
            }

            $participantSnapshot = $this->database->getReference('participants/' . $uid)->getSnapshot();
            
            if (!$participantSnapshot->exists()) {
                \Log::warning('Participant data not found in database', ['uid' => $uid]);
                
                // Try to get data from Firebase Auth instead
                $auth = app(\Kreait\Firebase\Contract\Auth::class);
                $firebaseUser = $auth->getUser($uid);
                
                $participantData = [
                    'uid' => $uid,
                    'namaLengkap' => $firebaseUser->displayName ?? 'User',
                    'email' => $firebaseUser->email,
                    'idPeserta' => 'RD' . substr($uid, 0, 8),
                    'registrationDate' => $firebaseUser->metadata->createdAt->format('c'),
                    'kategoriLomba' => 'N/A',
                    'nomorWA' => 'N/A',
                    'ukuranBaju' => 'N/A',
                    'pembayaran' => 'belum'
                ];
            } else {
                $participantData = $participantSnapshot->getValue();
            }
            
            \Log::info('Dashboard loaded successfully', ['uid' => $uid]);
            return view('participant.dashboard', compact('participantData'));

        } catch (\Exception $e) {
            \Log::error('Participant dashboard error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'uid' => session('participant_uid')
            ]);

            return redirect('/peserta/login')->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/peserta/login')->with('success', 'Logout berhasil.');
    }

    // Alternative login using phone number
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
            // Find participant by phone number
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
            $firebaseId = null;
            
            foreach ($participants->getValue() as $key => $participant) {
                $participantData = $participant;
                $firebaseId = $key;
                break; // Take the first (should be only one)
            }

            // Check password
            if (!Hash::check($request->password, $participantData['password'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah.'
                ], 401);
            }

            // Create session
            session([
                'participant_logged_in' => true,
                'participant_id' => $participantData['idPeserta'],
                'participant_firebase_id' => $firebaseId,
                'participant_email' => $participantData['email'],
                'participant_name' => $participantData['namaLengkap']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'redirect' => '/peserta/dashboard'
            ]);

        } catch (\Exception $e) {
            \Log::error('Participant phone login error', [
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
}
