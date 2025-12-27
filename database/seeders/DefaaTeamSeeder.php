<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaaTeamSeeder extends Seeder
{
    public function run()
    {
        $users = [
            // الشركاء (Admin)
            ['name' => 'د. عبدالرحمن الجمعه', 'email' => 'dr.aljumah@defaalegal.sa', 'role' => 'admin'],
            ['name' => 'د. أنس المطلق',      'email' => 'dr.anas@defaalegal.sa',    'role' => 'admin'],
            
            // المحامين (Lawyer)
            ['name' => 'محمد سليم',           'email' => 'm.slim@defaalegal.sa',     'role' => 'lawyer'],
            ['name' => 'رنيم الشلوي',         'email' => 'raneem@defaalegal.sa',     'role' => 'lawyer'],
            
            // المساعدين (Assistant)
            ['name' => 'مساعد أول',           'email' => 'Attorney_1@defaalegal.sa', 'role' => 'assistant'],
            ['name' => 'مساعد ثاني',          'email' => 'Attorney_2@defaalegal.sa', 'role' => 'assistant'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']], // البحث بالبريد لتجنب التكرار
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make('123456'), // كلمة المرور الموحدة
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
