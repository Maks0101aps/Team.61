<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    // Check if the table exists
    if (Schema::hasTable('task_participants')) {
        echo "task_participants table exists.\n";
        
        // Check columns
        $columns = Schema::getColumnListing('task_participants');
        echo "Columns: " . implode(', ', $columns) . "\n";
        
        // Try to execute the problematic query with parameters to see the results
        try {
            $result = DB::select("
                SELECT * FROM task_participants 
                WHERE task_id = 1 
                AND participant_type = ?
                LIMIT 1
            ", ['App\\Models\\Contact']);
            
            echo "Query executed successfully.\n";
            if (count($result) > 0) {
                echo "Found " . count($result) . " results. First result: \n";
                print_r($result[0]);
            } else {
                echo "No results found.\n";
            }
        } catch (\Exception $e) {
            echo "Query error: " . $e->getMessage() . "\n";
        }
        
        // Show actual table structure
        try {
            $tableInfo = DB::select("PRAGMA table_info(task_participants)");
            echo "Table structure:\n";
            print_r($tableInfo);
        } catch (\Exception $e) {
            echo "Table structure error: " . $e->getMessage() . "\n";
        }
        
        // Try a raw insert
        try {
            $taskId = 99; // Use a dummy task ID for testing
            DB::statement("
                INSERT INTO task_participants (task_id, participant_id, participant_type, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?)
            ", [$taskId, 1, 'App\\Models\\Contact', now(), now()]);
            
            echo "Test insert successful.\n";
        } catch (\Exception $e) {
            echo "Insert error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "task_participants table does not exist.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 