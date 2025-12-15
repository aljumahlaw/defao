<?php

namespace Database\Seeders;

use App\Models\DocumentActivity;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = Document::all();
        $users = User::all();

        if ($documents->isEmpty() || $users->isEmpty()) {
            return; // Skip if no documents or users
        }

        // Create activities for each document
        $documents->each(function ($document) use ($users) {
            // Create 2-5 activities per document
            $activityCount = rand(2, 5);
            
            DocumentActivity::factory($activityCount)->create([
                'document_id' => $document->id,
                'user_id' => $users->random()->id,
            ]);

            // First activity should always be 'created'
            DocumentActivity::where('document_id', $document->id)
                ->orderBy('created_at')
                ->first()
                ->update([
                    'action_type' => 'created',
                    'user_id' => $document->user_id, // Creator
                ]);
        });
    }
}
