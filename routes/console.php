<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Console\Commands\OptimizeProduction;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('users:list', function () {
    $users = User::select('id', 'name', 'email')->get()->toArray();

    if (empty($users)) {
        $this->info('No users found.');
        return;
    }

    $this->table(
        ['ID', 'Name', 'Email'],
        $users
    );
})->purpose('List all users');

// Alias لأمر الإنتاج: php artisan optimize:production
Artisan::command('optimize:production', function () {
    $this->call(OptimizeProduction::class);
})->purpose('Optimize configuration, routes, and views for production');
