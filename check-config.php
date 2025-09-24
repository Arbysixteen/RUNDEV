<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "==== CHECKING LARAVEL ENVIRONMENT CONFIG ====\n\n";

echo "Firebase Credentials Path: " . env('FIREBASE_CREDENTIALS', 'Not set') . "\n";
echo "Firebase Database URL: " . env('FIREBASE_DATABASE_URL', 'Not set') . "\n";

echo "\nChecking file existence:\n";
$credentialsPath = env('FIREBASE_CREDENTIALS', '');
if (file_exists($credentialsPath)) {
    echo "Credentials file EXISTS at: $credentialsPath\n";
} else {
    echo "Credentials file DOES NOT EXIST at: $credentialsPath\n";
}

echo "\n==== CONFIG CHECK COMPLETE ====\n";
