<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== USERS TABLE STRUCTURE ===\n";
$usersStructure = DB::select('PRAGMA table_info(users)');
print_r($usersStructure);

echo "\n=== EVENTS TABLE STRUCTURE ===\n";
$eventsStructure = DB::select('PRAGMA table_info(events)');
print_r($eventsStructure);

echo "\n=== CURRENT USER ===\n";
$user = \Auth::user();
if ($user) {
    echo "User ID: " . $user->id . "\n";
    echo "User role: " . $user->role . "\n";
    foreach ($user->getAttributes() as $key => $value) {
        echo "$key: " . (is_scalar($value) ? $value : json_encode($value)) . "\n";
    }
} else {
    echo "No authenticated user\n";
}

echo "\n=== ACCOUNTS TABLE ===\n";
$accounts = DB::table('accounts')->get();
print_r($accounts);

echo "\n=== FIRST USER FROM DATABASE ===\n";
$firstUser = DB::table('users')->first();
print_r($firstUser); 