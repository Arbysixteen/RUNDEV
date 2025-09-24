<?php
/**
 * Debug Helper Script untuk RUN DEV Event Registration
 * 
 * Script ini membantu mendiagnosis masalah dengan aplikasi RUN DEV Event Registration
 */
require __DIR__.'/vendor/autoload.php';

// Import Laravel core
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Set header output
header('Content-Type: text/plain');

echo "===== RUN DEV - DIAGNOSTIC TOOL =====\n\n";
echo "Waktu: " . date('Y-m-d H:i:s') . "\n";
echo "PHP Version: " . phpversion() . "\n";

// 1. Periksa konfigurasi .env Firebase
echo "\n--- ENVIRONMENT CONFIGURATION ---\n";
echo "APP_ENV: " . env('APP_ENV', 'Not set') . "\n";
echo "APP_URL: " . env('APP_URL', 'Not set') . "\n";
echo "Firebase Credentials: " . env('FIREBASE_CREDENTIALS', 'Not set') . "\n";
echo "Firebase Database URL: " . env('FIREBASE_DATABASE_URL', 'Not set') . "\n";

// 2. Periksa keberadaan file
echo "\n--- FILE CHECKS ---\n";
$credentialsPath = env('FIREBASE_CREDENTIALS', '');
if (file_exists($credentialsPath)) {
    echo "✅ Credentials file EXISTS at: $credentialsPath\n";
} else {
    echo "❌ Credentials file DOES NOT EXIST at: $credentialsPath\n";
}

// 3. Test Firebase connection
echo "\n--- FIREBASE CONNECTION TEST ---\n";
try {
    echo "Initializing Firebase connection...\n";
    
    $firebase = (new Kreait\Firebase\Factory)
        ->withServiceAccount($credentialsPath)
        ->withDatabaseUri(env('FIREBASE_DATABASE_URL', ''))
        ->createDatabase();
    
    echo "✅ Firebase connection established.\n";
    
    // Coba read data
    echo "Reading test data...\n";
    $reference = $firebase->getReference('test');
    $snapshot = $reference->getSnapshot();
    echo "Test data: " . json_encode($snapshot->getValue()) . "\n";
    
    // Coba write data
    echo "Writing test data...\n";
    $reference->set([
        'timestamp' => time(),
        'message' => 'Debug script test - ' . date('Y-m-d H:i:s'),
    ]);
    echo "✅ Test data written successfully.\n";
    
    // Baca lagi untuk memastikan
    $snapshot = $reference->getSnapshot();
    echo "New test data: " . json_encode($snapshot->getValue()) . "\n";
    
} catch (Exception $e) {
    echo "❌ Firebase connection error: " . $e->getMessage() . "\n";
}

// 4. Periksa API routes
echo "\n--- API ROUTES CHECK ---\n";
$routes = Route::getRoutes();
$apiRoutes = [];
foreach ($routes as $route) {
    if (strpos($route->uri(), 'api/') === 0) {
        $apiRoutes[] = $route->methods()[0] . ' ' . $route->uri() . ' => ' . $route->getActionName();
    }
}

if (count($apiRoutes) > 0) {
    echo "API Routes terdeteksi:\n";
    foreach ($apiRoutes as $route) {
        echo "- " . $route . "\n";
    }
} else {
    echo "❌ Tidak ada API routes yang terdeteksi.\n";
}

// 5. Periksa tabel 'participants' di Firebase
echo "\n--- PARTICIPANTS DATA CHECK ---\n";
try {
    $participantsRef = $firebase->getReference('participants');
    $snapshot = $participantsRef->getSnapshot();
    $participants = $snapshot->getValue();
    
    if (empty($participants)) {
        echo "❌ Tidak ada data peserta yang ditemukan.\n";
    } else {
        echo "✅ Data peserta ditemukan: " . count($participants) . " entries.\n";
        
        // Tampilkan 3 entri pertama saja untuk menjaga privasi
        $count = 0;
        foreach ($participants as $key => $participant) {
            if ($count >= 3) {
                echo "... dan " . (count($participants) - 3) . " peserta lainnya.\n";
                break;
            }
            
            echo "\nParticipant ID: " . $key . "\n";
            echo "- Nama: " . ($participant['namaLengkap'] ?? 'Unknown') . "\n";
            echo "- Email: " . (substr($participant['email'] ?? 'Unknown', 0, 3) . '***@' . strstr($participant['email'] ?? 'example.com', '@')) . "\n";
            echo "- Kategori: " . ($participant['kategoriLomba'] ?? 'Unknown') . "\n";
            echo "- Status: " . ($participant['pembayaran'] ?? 'Unknown') . "\n";
            $count++;
        }
    }
} catch (Exception $e) {
    echo "❌ Error saat memeriksa data peserta: " . $e->getMessage() . "\n";
}

echo "\n===== DIAGNOSTIC COMPLETE =====\n";
