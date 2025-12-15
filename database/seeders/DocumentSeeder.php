<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users or create some
        $users = User::all();
        if ($users->isEmpty()) {
            $users = User::factory(10)->create();
        }

        // Create 50 documents
        Document::factory(50)->create()->each(function ($document) use ($users) {
            // Assign random creator and assignee from existing users
            $document->update([
                'user_id' => $users->random()->id,
                'assignee_id' => $users->random()->id,
            ]);
        });
    }
}
