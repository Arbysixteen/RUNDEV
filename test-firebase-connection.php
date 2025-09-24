<?php
require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;

$serviceAccount = __DIR__ . '/run-event-cbb9c-firebase-adminsdk-fbsvc-c5bd10ffdc.json';
$databaseUrl = 'https://run-event-cbb9c-default-rtdb.firebaseio.com/';

try {
    echo "Initializing Firebase connection...\n";
    
    $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri($databaseUrl)
        ->createDatabase();
    
    echo "Firebase connection established.\n";
    
    echo "Writing test data...\n";
    $reference = $firebase->getReference('test');
    $reference->set([
        'timestamp' => time(),
        'message' => 'Firebase connection test direct script',
        'date' => date('Y-m-d H:i:s')
    ]);
    
    echo "Test data written successfully.\n";
    
    echo "Reading test data...\n";
    $snapshot = $reference->getSnapshot();
    echo "Test data: " . json_encode($snapshot->getValue()) . "\n";
    
    echo "Firebase connection test completed successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
