<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Event;
use App\Models\Task;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = Account::first() ?? Account::create(['name' => 'School Account']);
        $teachers = Teacher::all();
        $events = Event::all();
        $students = Student::all();
        $user = User::first() ?? User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);
        
        if ($teachers->isEmpty()) {
            echo "Warning: No teachers found to assign tasks.\n";
            return;
        }
        
        $tasksData = [
            [
                'title' => 'Домашнє завдання з математики',
                'content' => 'Вирішити завдання 10-15 з підручника на сторінці 25.',
                'due_date' => now()->addDays(3),
            ],
            [
                'title' => 'Підготовка до контрольної з фізики',
                'content' => 'Повторити закони Ньютона та вирішити приклади з минулого уроку.',
                'due_date' => now()->addDays(5),
            ],
            [
                'title' => 'Твір з української літератури',
                'content' => 'Написати твір-роздум на тему "Роль літератури в сучасному світі" обсягом не менше 300 слів.',
                'due_date' => now()->addDays(7),
            ],
            [
                'title' => 'Проєкт з історії',
                'content' => 'Підготувати презентацію про важливу історичну подію XX століття.',
                'due_date' => now()->addDays(14),
            ],
            [
                'title' => 'Лабораторна робота з біології',
                'content' => 'Підготувати звіт про проведену лабораторну роботу з дослідження клітин.',
                'due_date' => now()->addDays(10),
            ],
        ];
        
        foreach ($tasksData as $index => $data) {
            // Select event and teacher based on index
            $teacher = $teachers[$index % $teachers->count()];
            $event = $events->isNotEmpty() ? $events[$index % $events->count()] : null;
            
            $task = Task::create([
                'account_id' => $account->id,
                'event_id' => $event ? $event->id : null,
                'title' => $data['title'],
                'content' => $data['content'],
                'due_date' => $data['due_date'],
                'completed' => false,
                'created_by' => $user->id,
            ]);
            
            try {
                // Assign task to some students through task_participants table
                $selectedStudents = $students->random(min(5, $students->count()));
                foreach ($selectedStudents as $student) {
                    DB::table('task_participants')->insert([
                        'task_id' => $task->id,
                        'participant_id' => $student->id,
                        'participant_type' => Student::class,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                echo "Created task: {$task->title} and assigned to " . $selectedStudents->count() . " students\n";
            } catch (\Exception $e) {
                echo "Error assigning students to task: " . $e->getMessage() . "\n";
            }
        }
    }
} 