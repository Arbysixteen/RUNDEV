<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;

class EmailVerificationController extends Controller
{
    protected $auth;
    protected $database;

    public function __construct(Auth $auth, Database $database)
    {
        $this->auth = $auth;
        $this->database = $database;
    }

    /**
     * Verify email using verification token
     */
    public function verify(Request $request, $token)
    {
        try {
            // Decode token to get UID
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);
            
            if (count($parts) !== 2) {
                return redirect('/peserta/login')
                    ->with('error', 'Link verifikasi tidak valid.');
            }
            
            $uid = $parts[0];
            $email = $parts[1];
            
            // Update user in Firebase Auth to mark email as verified
            $this->auth->updateUser($uid, [
                'emailVerified' => true
            ]);
            
            // Update participant data in Realtime Database
            $participantRef = $this->database->getReference('participants/' . $uid);
            $participantRef->update([
                'emailVerified' => true,
                'emailVerifiedAt' => date('c')
            ]);
            
            \Log::info('Email verified successfully', [
                'uid' => $uid,
                'email' => $email
            ]);
            
            return redirect('/peserta/login')
                ->with('success', 'Email berhasil diverifikasi! Silakan login.');
                
        } catch (\Exception $e) {
            \Log::error('Email verification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect('/peserta/login')
                ->with('error', 'Terjadi kesalahan saat verifikasi email. Silakan coba lagi.');
        }
    }
    
    /**
     * Resend verification email
     */
    public function resend(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid'
            ], 422);
        }
        
        try {
            // Get user by email
            $user = $this->auth->getUserByEmail($request->email);
            
            // Check if already verified
            if ($user->emailVerified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email sudah diverifikasi. Silakan login.'
                ], 400);
            }
            
            // Generate verification token
            $token = base64_encode($user->uid . '|' . $user->email);
            $verificationUrl = url('/verify-email/' . $token);
            
            // Get participant data
            $participantSnapshot = $this->database->getReference('participants/' . $user->uid)->getSnapshot();
            $participantData = $participantSnapshot->getValue();
            
            // Send verification email
            Mail::send('emails.verify', [
                'name' => $participantData['namaLengkap'] ?? 'Peserta',
                'verificationUrl' => $verificationUrl
            ], function ($message) use ($user, $participantData) {
                $message->to($user->email, $participantData['namaLengkap'] ?? 'Peserta')
                    ->subject('Verifikasi Email - RUN DEV Event');
            });
            
            \Log::info('Verification email resent', [
                'uid' => $user->uid,
                'email' => $user->email
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Email verifikasi telah dikirim ulang. Silakan cek inbox Anda.'
            ]);
            
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan. Silakan daftar terlebih dahulu.'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Resend verification email error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim email. Silakan coba lagi.'
            ], 500);
        }
    }
}
