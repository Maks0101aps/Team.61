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
        
        // Define the relationships between parents and students based on last name
        $relationships = [
            // Петренко family
            ['last_name' => 'Петренко', 'parent_type' => ParentModel::TYPE_FATHER], // Петро Петренко
            ['last_name' => 'Петренко', 'parent_type' => ParentModel::TYPE_MOTHER], // Олена Петренко
            
            // Іваненко family
            ['last_name' => 'Іваненко', 'parent_type' => ParentModel::TYPE_FATHER], // Андрій Іваненко
            ['last_name' => 'Іваненко', 'parent_type' => ParentModel::TYPE_MOTHER], // Марія Іваненко
            
            // Коваленко family
            ['last_name' => 'Коваленко', 'parent_type' => ParentModel::TYPE_FATHER], // Сергій Коваленко
            ['last_name' => 'Коваленко', 'parent_type' => ParentModel::TYPE_MOTHER], // Ольга Коваленко
            
            // Мельник family
            ['last_name' => 'Мельник', 'parent_type' => ParentModel::TYPE_FATHER], // Віктор Мельник
            ['last_name' => 'Мельник', 'parent_type' => ParentModel::TYPE_MOTHER], // Наталія Мельник
            
            // Бондаренко family
            ['last_name' => 'Бондаренко', 'parent_type' => ParentModel::TYPE_FATHER], // Олег Бондаренко
            ['last_name' => 'Бондаренко', 'parent_type' => ParentModel::TYPE_MOTHER], // Тетяна Бондаренко
            
            // Шевченко family
            ['last_name' => 'Шевченко', 'parent_type' => ParentModel::TYPE_FATHER], // Михайло Шевченко
            ['last_name' => 'Шевченко', 'parent_type' => ParentModel::TYPE_MOTHER], // Ірина Шевченко
            
            // Савченко family
            ['last_name' => 'Савченко', 'parent_type' => ParentModel::TYPE_FATHER], // Григорій Савченко
            ['last_name' => 'Савченко', 'parent_type' => ParentModel::TYPE_MOTHER], // Катерина Савченко
        ];
        
        foreach ($relationships as $relation) {
            // Find parent
            $parent = ParentModel::where('last_name', $relation['last_name'])
                ->where('parent_type', $relation['parent_type'])
                ->first();
                
            if (!$parent) {
                echo "Warning: No parent found with last name {$relation['last_name']} and type {$relation['parent_type']}\n";
                continue;
            }
            
            // Find students with the same last name
            $students = Student::where('last_name', $relation['last_name'])->get();
            
            if ($students->isEmpty()) {
                echo "Warning: No students found with last name {$relation['last_name']}\n";
                continue;
            }
            
            // Create relationships for each student
            foreach ($students as $student) {
                DB::table('parent_student')->insert([
                    'parent_model_id' => $parent->id,
                    'student_id' => $student->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                echo "Created relationship: Parent {$parent->first_name} {$parent->last_name} ({$parent->parent_type_name}) -> Student {$student->first_name} {$student->last_name}\n";
            }
        }
        
        echo "Finished creating parent-student relationships.\n";
    }
}
