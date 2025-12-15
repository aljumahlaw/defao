<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users and documents
        $users = User::all();
        $documents = Document::all();

        if ($users->isEmpty()) {
            $users = User::factory(10)->create();
        }

        // Create 30 tasks
        Task::factory(30)->create()->each(function ($task) use ($users, $documents) {
            // Assign random creator and assignee
            $task->update([
                'user_id' => $users->random()->id,
                'assignee_id' => $users->random()->id,
            ]);

            // 70% of tasks have related documents
            if ($documents->isNotEmpty() && rand(1, 100) <= 70) {
                $task->update([
                    'document_id' => $documents->random()->id,
                ]);
            }
        });
    }
}
