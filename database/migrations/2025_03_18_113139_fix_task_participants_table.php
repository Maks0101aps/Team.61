<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete the existing migration
        DB::table('migrations')
            ->where('migration', '2025_03_12_081816_create_task_participants_table')
            ->delete();
            
        // Check if task_participants table exists
        if (Schema::hasTable('task_participants')) {
            // Backup existing data
            $data = [];
            try {
                $participants = DB::table('task_participants')->get();
                foreach ($participants as $participant) {
                    if (isset($participant->task_id)) {
                        $data[] = [
                            'task_id' => $participant->task_id,
                            'participant_id' => $participant->participant_id ?? 1, // Default if missing
                            'participant_type' => $participant->participant_type ?? 'App\\Models\\Contact', // Default if missing
                            'created_at' => $participant->created_at ?? now(),
                            'updated_at' => $participant->updated_at ?? now(),
                        ];
                    }
                }
            } catch (\Exception $e) {
                // If we can't read the table, it might be corrupted
                // We'll just recreate it
            }
            
            // Drop the existing table
            Schema::dropIfExists('task_participants');
            
            // Create the table with correct schema
            Schema::create('task_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('task_id')->constrained()->onDelete('cascade');
                $table->morphs('participant'); // This creates participant_id and participant_type columns
                $table->timestamps();
            });
            
            // Restore data if we had any
            if (!empty($data)) {
                DB::table('task_participants')->insert($data);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to implement down as this is a one-way fix
    }
};
