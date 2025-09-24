<?php

namespace App\Http\Controllers;

use App\Services\FirebaseAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseAdmin $firebase)
    {
        $this->firebase = $firebase;
    }

    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'namaLengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'kategoriLomba' => 'required|string|in:5K,10K,Half Marathon',
            'nomorWA' => 'required|string|min:10',
            'ukuranBaju' => 'required|string|in:S,M,L,XL,XXL',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate participant ID
            $participantId = 'RD' . time();
            
            // Prepare data
            $participantData = [
                'namaLengkap' => $request->namaLengkap,
                'email' => $request->email,
                'kategoriLomba' => $request->kategoriLomba,
                'nomorWA' => $request->nomorWA,
                'ukuranBaju' => $request->ukuranBaju,
                'idPeserta' => $participantId,
                'registrationDate' => date('c'), // ISO 8601
                'pembayaran' => 'belum'
            ];

            // Push to Firebase
            $key = $this->firebase->pushData('participants', $participantData);

            if ($key) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil',
                    'data' => [
                        'participantId' => $participantId,
                        'firebaseId' => $key
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data ke Firebase'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            
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
            // Get existing data
            $path = 'participants/' . $request->firebaseId;
            $participantData = $this->firebase->getData($path);

            if (!$participantData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data peserta tidak ditemukan'
                ], 404);
            }

            // Update payment status
            $participantData['pembayaran'] = 'lunas';
            $participantData['paymentDate'] = date('c');

            // Save updated data
            $success = $this->firebase->setData($path, $participantData);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status pembayaran berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status pembayaran'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Payment update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
