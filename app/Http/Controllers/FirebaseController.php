<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Contract\Database;
use App\Mail\RegistrationSuccess;

class FirebaseController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function register(Request $request)
    {
        // Log incoming request for debugging
        \Log::info('Registration request received', [
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
            // Check if email already exists
            $existingParticipants = $this->database->getReference('participants')
                ->orderByChild('email')
                ->equalTo($request->email)
                ->getSnapshot();
            
            if ($existingParticipants->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email sudah terdaftar. Silakan gunakan email lain atau login ke halaman peserta.'
                ], 422);
            }

            // Generate participant ID
            $participantId = 'RD' . time();

            // Prepare data
            $participantData = [
                'namaLengkap' => $request->namaLengkap,
                'email' => $request->email,
                'kategoriLomba' => $request->kategoriLomba,
                'nomorWA' => $request->nomorWA,
                'ukuranBaju' => $request->ukuranBaju,
                'password' => bcrypt($request->password), // Hash password
                'idPeserta' => $participantId,
                'registrationDate' => date('c'), // ISO 8601
                'pembayaran' => 'belum'
            ];

            // Log data
            \Log::info('Attempting to save participant data', ['data' => $participantData]);

            // Save to Firebase
            $newPost = $this->database->getReference('participants')->push($participantData);
            $firebaseId = $newPost->getKey();

            // Log success
            \Log::info('Successfully saved participant data', ['firebase_id' => $firebaseId]);

            // Send email notification
            try {
                Mail::to($request->email)->send(new RegistrationSuccess($participantData));
                \Log::info('Registration email sent successfully', ['email' => $request->email]);
            } catch (\Exception $emailError) {
                \Log::error('Failed to send registration email', ['error' => $emailError->getMessage()]);
                // Don't fail the registration if email fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil',
                'data' => [
                    'participantId' => $participantId,
                    'firebaseId' => $firebaseId
                ]
            ]);
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error saving participant data', [
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

    public function updatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firebaseId' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get reference
            $reference = $this->database->getReference('participants/' . $request->firebaseId);
            
            // Check if data exists
            $snapshot = $reference->getSnapshot();
            if (!$snapshot->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data peserta tidak ditemukan'
                ], 404);
            }

            $participantData = $snapshot->getValue();
            $participantData['pembayaran'] = 'lunas';
            $participantData['paymentDate'] = date('c');

            // Update data
            $reference->update($participantData);

            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating payment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // Method untuk test koneksi Firebase
    public function testConnection()
    {
        try {
            $reference = $this->database->getReference('test');
            $reference->set([
                'timestamp' => time(),
                'message' => 'Firebase connection test from Laravel'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Firebase connection successful',
                'reference_path' => $reference->getPath()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Firebase connection failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
