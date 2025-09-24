<?php
require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;

$serviceAccount = __DIR__ . '/firebase-credentials.json';
$databaseUrl = 'https://run-event-cbb9c-default-rtdb.firebaseio.com/';

try {
    echo "==== FIREBASE CONNECTION TEST ====\n\n";
    
    // Step 1: Check if service account file exists
    echo "1. Checking service account file...\n";
    if (!file_exists($serviceAccount)) {
        throw new Exception("Service account file not found at: $serviceAccount");
    }
    echo "   Service account file exists.\n\n";
    
    // Step 2: Verify service account file content
    echo "2. Verifying service account content...\n";
    $serviceAccountContent = json_decode(file_get_contents($serviceAccount), true);
    if (!isset($serviceAccountContent['project_id']) || $serviceAccountContent['project_id'] !== 'run-event-cbb9c') {
        throw new Exception("Invalid service account file. Project ID mismatch or missing.");
    }
    echo "   Service account content valid for project: " . $serviceAccountContent['project_id'] . "\n\n";
    
    // Step 3: Initialize Firebase
    echo "3. Initializing Firebase connection...\n";
    $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri($databaseUrl)
        ->createDatabase();
    echo "   Firebase connection established.\n\n";
    
    // Step 4: Test database read
    echo "4. Testing database read...\n";
    $reference = $firebase->getReference('test');
    $snapshot = $reference->getSnapshot();
    echo "   Current test data: " . json_encode($snapshot->getValue()) . "\n\n";
    
    // Step 5: Test database write
    echo "5. Testing database write...\n";
    $testData = [
        'timestamp' => time(),
        'message' => 'Firebase connection test - Debug Script',
        'date' => date('Y-m-d H:i:s')
    ];
    $reference->set($testData);
    echo "   Test data written successfully.\n\n";
    
    // Step 6: Verify write by reading again
    echo "6. Verifying data was written correctly...\n";
    $snapshot = $reference->getSnapshot();
    $writtenData = $snapshot->getValue();
    if ($writtenData['message'] === $testData['message']) {
        echo "   Data verified successfully!\n\n";
    } else {
        echo "   WARNING: Written data does not match what we tried to write!\n\n";
    }
    
    // Step 7: Test creating a participant record
    echo "7. Testing participant creation...\n";
    $participantData = [
        'namaLengkap' => 'Test User',
        'email' => 'test@example.com',
        'kategoriLomba' => '5K',
        'nomorWA' => '0812345678',
        'ukuranBaju' => 'M',
        'idPeserta' => 'TEST' . time(),
        'registrationDate' => date('c'),
        'pembayaran' => 'belum'
    ];
    
    $participantsRef = $firebase->getReference('participants');
    $newParticipant = $participantsRef->push($participantData);
    $participantKey = $newParticipant->getKey();
    
    echo "   Test participant created with key: $participantKey\n\n";
    
    // Step 8: List all participants
    echo "8. Listing all participants...\n";
    $participants = $participantsRef->getValue();
    if (empty($participants)) {
        echo "   No participants found in database!\n\n";
    } else {
        echo "   Found " . count($participants) . " participants:\n";
        foreach ($participants as $key => $participant) {
            echo "   - " . $key . ": " . ($participant['namaLengkap'] ?? 'Unknown') . " (" . ($participant['kategoriLomba'] ?? 'Unknown') . ")\n";
        }
        echo "\n";
    }
    
    echo "==== ALL TESTS PASSED SUCCESSFULLY! ====\n";
    
} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}
