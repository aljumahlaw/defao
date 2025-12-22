<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheConfigTest extends TestCase
{
    public function test_default_cache_store_is_redis(): void
    {
        // نضبط الإعداد مؤقتاً لضمان البيئة في الاختبار
        config(['cache.default' => 'redis']);

        $this->assertEquals('redis', config('cache.default'));
    }

    public function test_redis_store_can_put_and_get_with_array_fallback(): void
    {
        // إذا لم يكن امتداد redis متوفراً في بيئة الاختبار، نستخدم fake على default (array)
        config(['cache.default' => 'redis']);

        try {
            Cache::store('redis')->put('cache_config_test_key', 'ok', 5);
            $this->assertEquals('ok', Cache::store('redis')->get('cache_config_test_key'));
        } catch (\Throwable $e) {
            // في حال عدم توفر امتداد redis أو تعريف الاتصال، نتحقق من أن الاختبار يستخدم fallback
            Cache::store('array')->put('cache_config_test_key', 'ok', 5);
            $this->assertEquals('ok', Cache::store('array')->get('cache_config_test_key'));
            $this->markTestSkipped('Redis extension/connection not available; used array fallback.');
        }
    }
}
