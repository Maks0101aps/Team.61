<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Account;
use App\Models\User;
use App\Models\Contact;
use App\Models\Teacher;
use App\Models\ParentModel;
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

function createParticipant($type, $account_id) {
    $timestamp = time();
    
    switch ($type) {
        case 'student':
            $participant = Contact::create([
                'account_id' => $account_id,
                'first_name' => 'Student',
                'middle_name' => '',
                'last_name' => (string)$timestamp,
                'email' => "student{$timestamp}@example.com",
            ]);
            $participantType = 'App\\Models\\Contact';
            break;
        
        case 'teacher':
            $participant = Teacher::create([
                'account_id' => $account_id,
                'first_name' => 'Teacher',
                'middle_name' => '',
                'last_name' => (string)$timestamp,
                'email' => "teacher{$timestamp}@example.com",
            ]);
            $participantType = 'App\\Models\\Teacher';
            break;
            
        case 'parent':
            $participant = ParentModel::create([
                'account_id' => $account_id,
                'first_name' => 'Parent',
                'middle_name' => '',
                'last_name' => (string)$timestamp,
                'email' => "parent{$timestamp}@example.com",
            ]);
            $participantType = 'App\\Models\\ParentModel';
            break;
            
        default:
            throw new Exception("Invalid participant type: {$type}");
    }
    
    return ['participant' => $participant, 'type' => $participantType];
}

try {
    echo "TESTING POLYMORPHIC RELATIONSHIPS\n";
    echo "=================================\n\n";
    
    // Get or create account and user
    $account = Account::first();
    if (!$account) {
        $account = Account::create(['name' => 'Test Account']);
    }
    
    $user = User::first();
    if (!$user) {
        $user = User::create([
            'account_id' => $account->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'owner' => true,
        ]);
    }
    
    // Get or create an event
    $event = Event::first();
    if (!$event) {
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
    
    // Create a task
    $task = Task::create([
        'account_id' => $account->id,
        'event_id' => $event->id,
        'title' => 'Polymorphic Test Task',
        'content' => 'Testing all polymorphic relationships',
        'due_date' => now()->addDays(7),
        'completed' => false,
        'created_by' => $user->id,
    ]);
    echo "Created task with ID: " . $task->id . "\n\n";
    
    // Create and link different types of participants
    $participantTypes = ['student', 'teacher', 'parent'];
    
    foreach ($participantTypes as $type) {
        try {
            echo "Testing {$type} relationship...\n";
            
            // Create participant
            $result = createParticipant($type, $account->id);
            $participant = $result['participant'];
            $participantType = $result['type'];
            
            echo "Created {$type} with ID: " . $participant->id . "\n";
            
            // Link to task
            Capsule::table('task_participants')->insert([
                'task_id' => $task->id,
                'participant_id' => $participant->id,
                'participant_type' => $participantType,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "Linked task and {$type}\n";
            
            // Check the relationship
            $relationshipMethod = $type . 's'; // students, teachers, parents
            $relatedModels = $task->$relationshipMethod()->get();
            
            echo "Retrieved " . $relatedModels->count() . " {$type}s through Task->{$relationshipMethod}() relationship\n";
            
            if ($relatedModels->count() > 0) {
                echo "First {$type} name: " . $relatedModels->first()->first_name . " " . $relatedModels->first()->last_name . "\n";
            }
            
            echo "\n";
            
        } catch (\Exception $e) {
            echo "Error with {$type} relationship: " . $e->getMessage() . "\n";
            echo "Line: " . $e->getLine() . "\n\n";
        }
    }
    
    // Final verification - get all participants
    echo "SUMMARY\n";
    echo "=======\n";
    
    $task = Task::find($task->id); // Refresh from database
    
    foreach ($participantTypes as $type) {
        $relationshipMethod = $type . 's'; // students, teachers, parents
        $count = $task->$relationshipMethod()->count();
        echo "Task has {$count} {$type}s\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Error type: " . get_class($e) . "\n";
} 