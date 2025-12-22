<?php

namespace Tests\Feature;

use App\Livewire\Documents\DocumentTable;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DocumentTableEagerTest extends TestCase
{
    public function test_eager_loading_applies_only_for_large_pages_or_search(): void
    {
        $user = User::factory()->create();
        $assignee = User::factory()->create();

        // إنشاء بعض الوثائق للمستخدم الحالي
        Document::factory()->count(5)->create([
            'user_id' => $user->id,
            'assignee_id' => $assignee->id,
        ]);

        // السيناريو 1: perPage صغير (5) ولا يوجد search → بدون eager relations
        DB::flushQueryLog();
        DB::enableQueryLog();

        /** @var DocumentTable $componentSmall */
        $componentSmall = app(DocumentTable::class);
        $componentSmall->perPage = 5;

        $this->actingAs($user);
        $componentSmall->documents();

        $smallQueries = collect(DB::getQueryLog())->count();

        // السيناريو 2: perPage كبير (20) → مع eager relations
        DB::flushQueryLog();
        DB::enableQueryLog();

        /** @var DocumentTable $componentLarge */
        $componentLarge = app(DocumentTable::class);
        $componentLarge->perPage = 20;

        $this->actingAs($user);
        $componentLarge->documents();

        $largeQueries = collect(DB::getQueryLog())->count();

        // يجب أن تزيد الاستعلامات في الحالة الثانية بسبب eager loading (استعلام العلاقات)
        $this->assertGreaterThan($smallQueries, $largeQueries);
    }
}
