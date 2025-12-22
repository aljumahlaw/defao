<?php

namespace Tests\Feature;

use App\Livewire\Tasks\TaskList;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TaskListSearchTest extends TestCase
{
    public function test_search_matches_title_and_description(): void
    {
        $user = User::factory()->create();

        // مهام تحتوي الكلمة "urgent"
        Task::factory()->create(['title' => 'urgent task A', 'description' => 'desc', 'user_id' => $user->id]);
        Task::factory()->create(['title' => 'something', 'description' => 'very urgent work', 'user_id' => $user->id]);
        Task::factory()->create(['title' => 'another urgent item', 'description' => 'text', 'user_id' => $user->id]);

        // مهام أخرى لا تحتوي الكلمة
        Task::factory()->count(7)->create(['title' => 'normal', 'description' => 'regular', 'user_id' => $user->id]);

        $this->actingAs($user);

        DB::flushQueryLog();
        DB::enableQueryLog();

        /** @var TaskList $component */
        $component = app(TaskList::class);
        $component->search = 'urgent';

        $paginator = $component->tasks();
        $titles = collect($paginator->items())->pluck('title')->implode(' ');

        $this->assertStringContainsString('urgent task A', $titles);
        $this->assertStringContainsString('something', $titles); // matched via description
        $this->assertStringContainsString('another urgent item', $titles);

        // 3 نتائج فقط يجب أن تظهر
        $this->assertCount(3, $paginator->items());

        $queries = DB::getQueryLog();
        $this->assertNotEmpty($queries); // مجرد تسجيل لعدد الاستعلامات المستخدم
    }
}
