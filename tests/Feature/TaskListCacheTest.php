<?php

namespace Tests\Feature;

use App\Livewire\Tasks\TaskList;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TaskListCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_counts_are_cached_and_correct_for_user(): void
    {
        $user = User::factory()->create();

        Task::factory()->count(2)->create(['status' => 'pending']);
        Task::factory()->count(3)->create(['status' => 'in_progress']);
        Task::factory()->count(4)->create(['status' => 'completed']);
        Task::factory()->count(5)->create(['status' => 'overdue']);

        $expected = [
            'pending' => 2,
            'in_progress' => 3,
            'completed' => 4,
            'overdue' => 5,
        ];

        Cache::shouldReceive('remember')
            ->once()
            ->withArgs(function ($key, $ttl, $callback) use ($user, $expected) {
                // تحقق من مفتاح الكاش والمدة
                $this->assertEquals('task_stats_' . $user->id, $key);
                $this->assertEquals(300, $ttl);

                $result = $callback();
                $this->assertSame($expected, $result);

                return true;
            })
            ->andReturn($expected);

        $this->actingAs($user);

        /** @var TaskList $component */
        $component = app(TaskList::class);

        $stats = $component->statusCounts();

        $this->assertSame($expected, $stats);
    }

    public function test_status_counts_cache_key_is_unique_per_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Task::factory()->count(1)->create(['status' => 'pending']);

        $seenKeys = [];

        Cache::shouldReceive('remember')
            ->twice()
            ->andReturnUsing(function ($key, $ttl, $callback) use (&$seenKeys) {
                $seenKeys[] = $key;
                return $callback();
            });

        $this->actingAs($user1);
        app(TaskList::class)->statusCounts();

        $this->actingAs($user2);
        app(TaskList::class)->statusCounts();

        $this->assertEquals([
            'task_stats_' . $user1->id,
            'task_stats_' . $user2->id,
        ], $seenKeys);
    }
}
