<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\NotificationSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 users
        $users = User::factory(10)->create();

        // Create test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create notification settings for all users
        $users->each(function ($user) {
            NotificationSetting::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        NotificationSetting::factory()->create([
            'user_id' => $testUser->id,
        ]);

        // Seed documents
        $this->call([
            DocumentSeeder::class,
        ]);

        // Seed tasks (depends on documents)
        $this->call([
            TaskSeeder::class,
        ]);

        // Seed document activities (depends on documents)
        $this->call([
            DocumentActivitySeeder::class,
        ]);
    }
}
