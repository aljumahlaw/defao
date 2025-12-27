<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Admin (static access داخل class method = صحيح)
        $admin = User::firstOrCreate(
            ['email' => 'admin@defao.com'],
            [
                'name' => 'Defao Admin',
                'password' => Hash::make('DefaoAdmin2025!'), // ✅ قوي
            ]
        );

        // ✅ static access صحيح هنا (داخل class method)
        $admin->update([
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
        ]);

        // ✅ باقي المستخدمين
        User::whereNull('role')->update([
            'role' => User::ROLE_LAWYER,
            'is_active' => true,
        ]);
    }
}









