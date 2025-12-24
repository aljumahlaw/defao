<?php

/**
 * Create Admin User Script
 * Run: php create-admin-user.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create admin user
$admin = User::firstOrCreate(
    ['email' => 'admin@example.com'],
    [
        'name' => 'Admin User',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]
);

echo "✅ Admin user created successfully!\n";
echo "📧 Email: admin@example.com\n";
echo "🔑 Password: password\n";
echo "\n";
echo "You can now login with these credentials.\n";
