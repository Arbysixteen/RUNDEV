<?php
require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;

$serviceAccount = __DIR__ . '/run-event-cbb9c-firebase-adminsdk-fbsvc-c5bd10ffdc.json';
$databaseUrl = 'https://run-event-cbb9c-default-rtdb.firebaseio.com/';

try {
    echo "===== CHECKING FIREBASE DATA =====\n\n";
    
    echo "1. Initializing Firebase connection...\n";
    $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri($databaseUrl)
        ->createDatabase();
    echo "   Firebase connection established.\n\n";
    
    echo "2. Retrieving participants data...\n";
    $reference = $firebase->getReference('participants');
    $snapshot = $reference->getSnapshot();
    $data = $snapshot->getValue();
    
    if (empty($data)) {
        echo "   No participants found in database!\n";
    } else {
        echo "   Found " . count($data) . " participants:\n\n";
        foreach ($data as $key => $participant) {
            echo "   - ID: " . $key . "\n";
            echo "     Name: " . ($participant['namaLengkap'] ?? 'Unknown') . "\n";
            echo "     Email: " . ($participant['email'] ?? 'Unknown') . "\n";
            echo "     Category: " . ($participant['kategoriLomba'] ?? 'Unknown') . "\n";
            echo "     Registration Date: " . ($participant['registrationDate'] ?? 'Unknown') . "\n";
            echo "     Payment Status: " . ($participant['pembayaran'] ?? 'Unknown') . "\n\n";
        }
    }
    
    echo "===== FIREBASE DATA CHECK COMPLETE =====\n";
    
} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}
