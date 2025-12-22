<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin';
    protected $description = 'Create an admin user for login';

    public function handle()
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->info('✅ Admin user created successfully!');
        $this->info('📧 Email: admin@example.com');
        $this->info('🔑 Password: password');
        $this->newLine();
        $this->info('You can now login at: http://127.0.0.1:8000/login');

        return Command::SUCCESS;
    }
}

