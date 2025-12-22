<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * نستخدم اسم داخلي مختلف لتفادي أي تعارض مع alias في routes/console.
     */
    protected $signature = 'optimize:production:run';

    /**
     * The console command description.
     */
    protected $description = 'Optimize configuration, routes, and views for production';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');

        $this->info('✅ Production optimized!');

        return self::SUCCESS;
    }
}
