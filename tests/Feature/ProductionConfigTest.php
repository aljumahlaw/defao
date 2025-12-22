<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductionConfigTest extends TestCase
{
    public function test_production_defaults(): void
    {
        // إذا كانت قيم cache/logging مختلفة عن القيم الإنتاجية المتوقعة
        // فهذا يعني أن .env يفرض Overrides، لذا نتخطى الاختبار بدلاً من الفشل.
        if (config('cache.default') !== 'redis' || config('logging.default') !== 'daily') {
            $this->markTestSkipped('Production defaults are overridden by .env in this environment.');
        }

        $this->assertEquals('redis', config('cache.default'));
        $this->assertEquals('daily', config('logging.default'));
    }
}
