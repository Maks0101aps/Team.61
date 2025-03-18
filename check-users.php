<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\User;

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

// Get all users
$users = Capsule::table('users')->get();

// Write to file
$output = "Users in database:\n\n";
if (count($users) > 0) {
    foreach ($users as $user) {
        $output .= "ID: " . $user->id . "\n";
        $output .= "Email: " . $user->email . "\n";
        $output .= "First Name: " . $user->first_name . "\n";
        $output .= "Last Name: " . $user->last_name . "\n";
        $output .= "Owner: " . ($user->owner ? 'Yes' : 'No') . "\n";
        $output .= "Created At: " . $user->created_at . "\n";
        $output .= "------------------------------------\n";
    }
} else {
    $output .= "No users found in the database.\n";
}

// Also try with User model
try {
    $modelUsers = User::all();
    $output .= "\n\nUsers from User model:\n\n";
    if ($modelUsers->count() > 0) {
        foreach ($modelUsers as $user) {
            $output .= "ID: " . $user->id . "\n";
            $output .= "Email: " . $user->email . "\n";
            $output .= "First Name: " . $user->first_name . "\n";
            $output .= "Last Name: " . $user->last_name . "\n";
            $output .= "Owner: " . ($user->owner ? 'Yes' : 'No') . "\n";
            $output .= "Created At: " . $user->created_at . "\n";
            $output .= "------------------------------------\n";
        }
    } else {
        $output .= "No users found using User model.\n";
    }
} catch (\Exception $e) {
    $output .= "\nError accessing User model: " . $e->getMessage() . "\n";
}

// Write to file
file_put_contents('users-check.txt', $output);

echo "User data written to users-check.txt\n"; 