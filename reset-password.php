<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Hash;

// Set up the database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'sqlite',
    'database'  => 'C:\-projects\projectsc\school61project\database\database.sqlite',
    'prefix'    => '',
]);

// Make this Capsule instance available globally
$capsule->setAsGlobal();

// Setup the Eloquent ORM
$capsule->bootEloquent();

// New password to set
$newPassword = 'secret';
$email = 'johndoe@example.com';

// Hash the password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the user's password
$updated = Capsule::table('users')
    ->where('email', $email)
    ->update(['password' => $hashedPassword]);

if ($updated) {
    echo "Password for user {$email} has been reset to '{$newPassword}'.\n";
} else {
    echo "Failed to update password for user {$email}.\n";
} 