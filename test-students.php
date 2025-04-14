<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Task;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;

echo "Testing Task-Students relationship directly...\n";

try {
    // Try the query that was failing
    $result = DB::select("
        SELECT contacts.*, task_participants.task_id as pivot_task_id, 
        task_participants.participant_id as pivot_participant_id, 
        task_participants.participant_type as pivot_participant_type 
        FROM contacts 
        INNER JOIN task_participants ON contacts.id = task_participants.participant_id 
        WHERE task_participants.task_id = 1 
        AND task_participants.participant_type = ?
        AND contacts.deleted_at IS NULL
    ", ['App\\Models\\Contact']);
    
    echo "Query executed successfully!\n";
    echo "Found " . count($result) . " students related to the task.\n";
    
    // Let's also try inserting a test relationship
    try {
        // First, check if we have a task
        $task = Task::first();
        $contact = Contact::first();
        
        if (!$task) {
            echo "No tasks available for testing.\n";
        } elseif (!$contact) {
            echo "No contacts available for testing.\n";
        } else {
            echo "Found task ID: " . $task->id . " and contact ID: " . $contact->id . "\n";
            
            // Try to attach the contact to the task
            echo "Attaching contact to task...\n";
            $task->students()->attach($contact->id);
            
            echo "Successfully attached contact to task!\n";
            
            // Try to retrieve again
            $students = $task->students;
            echo "Successfully retrieved " . count($students) . " students for the task.\n";
        }
    } catch (\Exception $e) {
        echo "Error with relationship: " . $e->getMessage() . "\n";
    }
} catch (\Exception $e) {
    echo "Query failed: " . $e->getMessage() . "\n";
} 