<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Task;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

// Redirect output to a file
$logFile = __DIR__ . '/test-output.log';
file_put_contents($logFile, "=== TESTING TASK MODEL RELATIONSHIPS ===\n", FILE_APPEND);

function logLine($message) {
    global $logFile;
    echo $message . "\n";
    file_put_contents($logFile, $message . "\n", FILE_APPEND);
}

try {
    // Find a task
    $task = Task::first();
    
    if (!$task) {
        logLine("No tasks found in the database.");
        
        // First, we need an event
        $event = Event::first();
        
        if (!$event) {
            logLine("No events found. Creating a test event...");
            
            try {
                $event = Event::create([
                    'account_id' => 1,
                    'title' => 'Test Event',
                    'type' => 'personal',
                    'start_date' => now(),
                    'duration' => 60, // 1 hour
                    'created_by' => 1,
                ]);
                
                logLine("Created event with ID: " . $event->id);
            } catch (\Exception $e) {
                logLine("Error creating event: " . $e->getMessage());
                exit(1);
            }
        } else {
            logLine("Found existing event with ID: " . $event->id);
        }
        
        logLine("Creating a test task...");
        
        try {
            // Create a test task
            $task = Task::create([
                'account_id' => 1,
                'event_id' => $event->id,
                'title' => 'Test Task',
                'content' => 'Test Content',
                'due_date' => now(),
                'completed' => false,
                'created_by' => 1,
            ]);
            
            logLine("Created task with ID: " . $task->id);
        } catch (\Exception $e) {
            logLine("Error creating task: " . $e->getMessage());
            exit(1);
        }
    } else {
        logLine("Found task with ID: " . $task->id);
    }
    
    // Test the students relationship
    try {
        logLine("Testing students relationship...");
        $students = $task->students;
        logLine("Successfully accessed students relationship. Found " . count($students) . " students.");
    } catch (\Exception $e) {
        logLine("Error accessing students relationship: " . $e->getMessage());
    }
    
    // Test the teachers relationship
    try {
        logLine("Testing teachers relationship...");
        $teachers = $task->teachers;
        logLine("Successfully accessed teachers relationship. Found " . count($teachers) . " teachers.");
    } catch (\Exception $e) {
        logLine("Error accessing teachers relationship: " . $e->getMessage());
    }
    
    // Test the parents relationship
    try {
        logLine("Testing parents relationship...");
        $parents = $task->parents;
        logLine("Successfully accessed parents relationship. Found " . count($parents) . " parents.");
    } catch (\Exception $e) {
        logLine("Error accessing parents relationship: " . $e->getMessage());
    }
    
    logLine("=== TESTING COMPLETE ===");
} catch (\Exception $e) {
    logLine("Error: " . $e->getMessage());
} 