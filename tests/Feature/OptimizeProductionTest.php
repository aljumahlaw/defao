<?php

namespace Tests\Feature;

use Tests\TestCase;

class OptimizeProductionTest extends TestCase
{
    public function test_optimize_production_command_caches_configuration(): void
    {
        // احذف ملف config cache إن وجد لضمان أن الأمر يعيد إنشاؤه
        $configCachePath = base_path('bootstrap/cache/config.php');
        if (file_exists($configCachePath)) {
            @unlink($configCachePath);
        }

        $this->artisan('optimize:production')
            ->assertExitCode(0);

        $this->assertFileExists($configCachePath);
    }
}
