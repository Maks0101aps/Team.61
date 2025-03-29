<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ParentStudentRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Empty the parent_student table to start fresh
        DB::table('parent_student')->truncate();
        
        // Get all parent users
        $parentUsers = User::where('role', User::ROLE_PARENT)->get();
        echo "Found " . $parentUsers->count() . " parent users\n";
        
        // For each parent user, find or create appropriate student relationships
        foreach ($parentUsers as $parentUser) {
            // Find the parent model
            $parent = ParentModel::where('email', $parentUser->email)->first();
            
            if (!$parent) {
                echo "Warning: No parent model found for user {$parentUser->first_name} {$parentUser->last_name} ({$parentUser->email})\n";
                continue;
            }
            
            echo "Processing parent: {$parent->first_name} {$parent->last_name} (ID: {$parent->id})\n";
            
            // Find students with a matching last name - this is a heuristic approach
            // In a real system, you'd have proper family relationships defined
            $matchingStudents = Student::where('last_name', $parentUser->last_name)->get();
            
            if ($matchingStudents->count() > 0) {
                echo "Found {$matchingStudents->count()} students with matching last name\n";
                
                foreach ($matchingStudents as $student) {
                    echo "Creating relationship between parent {$parent->id} and student {$student->id}\n";
                    
                    // Create the relationship
                    DB::table('parent_student')->insert([
                        'parent_model_id' => $parent->id,
                        'student_id' => $student->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } else {
                echo "No students found with matching last name for parent {$parent->first_name} {$parent->last_name}\n";
                
                // Get a student for this parent based on the parent's ID
                // This is a fallback approach to ensure each parent has at least one student
                $studentId = $parent->id % Student::count() + 1;
                $student = Student::find($studentId);
                
                if ($student) {
                    echo "Using fallback: Creating relationship between parent {$parent->id} and student {$student->id}\n";
                    
                    // Create the relationship
                    DB::table('parent_student')->insert([
                        'parent_model_id' => $parent->id,
                        'student_id' => $student->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
        
        echo "Finished creating parent-student relationships.\n";
    }
}
