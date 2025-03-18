<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Account;
use App\Models\User;
use App\Models\Contact;
use App\Models\Task;
use App\Models\Event;

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

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    echo "Creating test account, user, event, task, and contact...\n";
    
    // Check for existing account
    echo "Checking for existing account...\n";
    $account = Account::first();
    if (!$account) {
        echo "No account found, creating one...\n";
        $account = Account::create([
            'name' => 'Test Account',
        ]);
    }
    echo "Using account with ID: " . $account->id . "\n";
    
    // Check for existing user
    echo "Checking for existing user...\n";
    $user = User::first();
    if (!$user) {
        echo "No user found, creating one...\n";
        $user = User::create([
            'account_id' => $account->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'owner' => true,
        ]);
    }
    echo "Using user with ID: " . $user->id . "\n";
    
    // Check for existing event
    echo "Checking for existing event...\n";
    $event = Event::first();
    if (!$event) {
        echo "No event found, creating one...\n";
        $event = Event::create([
            'account_id' => $account->id,
            'organizer_id' => $user->id,
            'title' => 'Test Event',
            'description' => 'Test Description',
            'date_start' => now(),
            'date_end' => now()->addHour(),
            'time_start' => now()->format('H:i'),
            'time_end' => now()->addHour()->format('H:i'),
            'location' => 'Test Location',
            'is_online' => false,
            'color' => '#6495ED',
        ]);
    }
    echo "Using event with ID: " . $event->id . "\n";
    
    // Create a task
    echo "Creating task...\n";
    try {
        $task = Task::create([
            'account_id' => $account->id,
            'event_id' => $event->id,
            'title' => 'Test Task',
            'content' => 'Test Task Description',
            'due_date' => now()->addDays(7),
            'completed' => false,
            'created_by' => $user->id,
        ]);
        echo "Created task with ID: " . $task->id . "\n";
    } catch (\Exception $e) {
        echo "Error creating task: " . $e->getMessage() . "\n";
        // Try to get task schema
        echo "Task schema: \n";
        print_r(Capsule::schema()->getColumnListing('tasks'));
        throw $e;
    }
    
    // Create a student contact
    echo "Creating contact...\n";
    $timestamp = time();
    try {
        $contact = Contact::create([
            'account_id' => $account->id,
            'first_name' => 'Student',
            'middle_name' => '',
            'last_name' => (string)$timestamp,
            'email' => "student{$timestamp}@example.com",
        ]);
        echo "Created contact with ID: " . $contact->id . "\n";
    } catch (\Exception $e) {
        echo "Error creating contact: " . $e->getMessage() . "\n";
        // Try to get contact schema
        echo "Contact schema: \n";
        print_r(Capsule::schema()->getColumnListing('contacts'));
        throw $e;
    }
    
    // Link task and contact
    echo "Linking task and contact...\n";
    try {
        Capsule::table('task_participants')->insert([
            'task_id' => $task->id,
            'participant_id' => $contact->id,
            'participant_type' => 'App\\Models\\Contact',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "Linked task and contact\n";
    } catch (\Exception $e) {
        echo "Error linking task and contact: " . $e->getMessage() . "\n";
        // Try to get task_participants schema
        echo "Task participants schema: \n";
        print_r(Capsule::schema()->getColumnListing('task_participants'));
        throw $e;
    }
    
    // Verify link by getting students for this task
    echo "Verifying link...\n";
    $students = Capsule::table('task_participants')
        ->where('task_id', $task->id)
        ->where('participant_type', 'App\\Models\\Contact')
        ->get();
    
    echo "Found " . count($students) . " students linked to task ID " . $task->id . "\n";
    
    // Check if we can retrieve the contacts through the task relationship
    echo "Testing Task-Contact relationship...\n";
    $taskFromDB = Task::find($task->id);
    if ($taskFromDB) {
        echo "Task found in DB with ID: " . $taskFromDB->id . "\n";
        
        // Debug Task model
        echo "Task model has the following methods: \n";
        echo "- Has students() method: " . (method_exists($taskFromDB, 'students') ? 'Yes' : 'No') . "\n";
        
        try {
            $contacts = $taskFromDB->students()->get();
            echo "Retrieved " . $contacts->count() . " contacts through Task model relationship\n";
            
            if ($contacts->count() > 0) {
                echo "First contact name: " . $contacts->first()->first_name . " " . $contacts->first()->last_name . "\n";
            } else {
                echo "No contacts found through the relationship\n";
            }
        } catch (\Exception $e) {
            echo "Error retrieving contacts: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Task not found in DB\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Error type: " . get_class($e) . "\n";
}