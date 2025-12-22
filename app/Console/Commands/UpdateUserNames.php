<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserNames extends Command
{
    protected $signature = 'users:update-names';
    protected $description = 'Update user names: خالد علي → د.أنس, محمد سعيد → د.عبدالرحمن, طارق أحمد → فيصل';

    public function handle()
    {
        $updates = [
            'خالد علي' => 'د.أنس',
            'محمد سعيد' => 'د.عبدالرحمن',
            'طارق أحمد' => 'فيصل',
        ];

        $totalUpdated = 0;

        foreach ($updates as $oldName => $newName) {
            $count = User::where('name', $oldName)->update(['name' => $newName]);
            $totalUpdated += $count;
            
            if ($count > 0) {
                $this->info("✅ Updated {$count} user(s) from '{$oldName}' to '{$newName}'");
            } else {
                $this->comment("ℹ️  No users found with name '{$oldName}'");
            }
        }

        if ($totalUpdated > 0) {
            $this->newLine();
            $this->info("✅ Total users updated: {$totalUpdated}");
        } else {
            $this->warn("⚠️  No users were updated. Make sure the names match exactly.");
        }

        return Command::SUCCESS;
    }
}
