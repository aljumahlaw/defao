<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\DocumentActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DocumentDetailN1Test extends TestCase
{
    use RefreshDatabase;

    public function test_activity_log_eager_loading_reduces_query_count(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->create([
            'user_id' => $user->id,
        ]);

        // إنشاء 10 نشاطات للوثيقة نفسها والمستخدم نفسه
        DocumentActivity::factory()->count(10)->create([
            'document_id' => $document->id,
            'user_id' => $user->id,
        ]);

        // السيناريو 1: بدون with('user')  → N+1
        DB::flushQueryLog();
        DB::enableQueryLog();

        $activitiesWithout = DocumentActivity::where('document_id', $document->id)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($activitiesWithout as $activity) {
            // كل استدعاء user->name بدون eager loading يسبب استعلام إضافي
            $activity->user->name;
        }

        $withoutQueries = count(DB::getQueryLog());

        // السيناريو 2: مع with('user')  → استعلام واحد إضافي فقط
        DB::flushQueryLog();
        DB::enableQueryLog();

        $activitiesWith = DocumentActivity::with('user')
            ->where('document_id', $document->id)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($activitiesWith as $activity) {
            $activity->user->name;
        }

        $withQueries = count(DB::getQueryLog());

        // تحقق تقريبي: without ≈ 11 (1 + 10), with ≈ 2 (1 + 1)
        $this->assertGreaterThan($withQueries, $withoutQueries, 'Eager loading should reduce query count');
        $this->assertEquals(2, $withQueries, 'Expected ~2 queries with eager loading (activities + users)');
    }
}
