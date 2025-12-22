<?php

namespace Tests\Feature;

use Illuminate\Filesystem\Filesystem;
use Tests\TestCase;

class SearchDebounceTest extends TestCase
{
    public function test_documents_table_has_debounced_search(): void
    {
        $filesystem = new Filesystem();
        $content = $filesystem->get(resource_path('views/livewire/documents/document-table.blade.php'));

        $this->assertStringContainsString('wire:model.live.debounce.300ms="search"', $content);
    }

    public function test_tasks_list_has_debounced_search(): void
    {
        $filesystem = new Filesystem();
        $content = $filesystem->get(resource_path('views/livewire/tasks/task-list.blade.php'));

        $this->assertStringContainsString('wire:model.live.debounce.300ms="search"', $content);
    }
}
