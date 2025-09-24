<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FirebaseAdmin
{
    private $serviceAccountPath;
    private $databaseURL;

    public function __construct()
    {
        $this->serviceAccountPath = storage_path('app/firebase/run-event-cbb9c-firebase-adminsdk-fbsvc-c5bd10ffdc.json');
        $this->databaseURL = 'https://run-event-cbb9c-default-rtdb.firebaseio.com/';
    }

    /**
     * Get an access token for Firebase Admin SDK
     * 
     * @return string
     */
    private function getAccessToken()
    {
        if (!file_exists($this->serviceAccountPath)) {
            throw new \Exception('Firebase service account file does not exist');
        }

        $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);
        
        // Generate JWT token claim
        $now = time();
        $payload = [
            'iss' => $serviceAccount['client_email'],
            'sub' => $serviceAccount['client_email'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.database'
        ];
        
        $privateKey = $serviceAccount['private_key'];
        
        // Sign JWT with private key
        $jwt = $this->signJwt($payload, $privateKey);
        
        // Exchange JWT for access token
        $response = Http::post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);
        
        if (!$response->successful()) {
            throw new \Exception('Could not obtain Firebase access token: ' . $response->body());
        }
        
        $data = $response->json();
        return $data['access_token'];
    }
    
    /**
     * Sign JWT with private key
     * 
     * @param array $payload
     * @param string $privateKey
     * @return string
     */
    private function signJwt($payload, $privateKey)
    {
        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];
        
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
        
        $signature = '';
        openssl_sign(
            $base64UrlHeader . '.' . $base64UrlPayload,
            $signature,
            $privateKey,
            'SHA256'
        );
        
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }
    
    /**
     * Get data from Firebase Realtime Database
     * 
     * @param string $path
     * @return array|null
     */
    public function getData($path)
    {
        try {
            $token = $this->getAccessToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get($this->databaseURL . $path . '.json');
            
            return $response->json();
        } catch (\Exception $e) {
            \Log::error('Firebase getData error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Write data to Firebase Realtime Database
     * 
     * @param string $path
     * @param array $data
     * @return bool
     */
    public function setData($path, $data)
    {
        try {
            $token = $this->getAccessToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->put($this->databaseURL . $path . '.json', $data);
            
            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('Firebase setData error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Push data to Firebase Realtime Database
     * 
     * @param string $path
     * @param array $data
     * @return string|null The generated key
     */
    public function pushData($path, $data)
    {
        try {
            $token = $this->getAccessToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($this->databaseURL . $path . '.json', $data);
            
            if ($response->successful()) {
                $result = $response->json();
                return $result['name'] ?? null; // Firebase returns the generated key as 'name'
            }
            
            return null;
        } catch (\Exception $e) {
            \Log::error('Firebase pushData error: ' . $e->getMessage());
            return null;
        }
    }
}
