<?php

namespace Tests\Feature;

use Illuminate\Filesystem\Filesystem;
use Tests\TestCase;

class SidebarShortcutsTest extends TestCase
{
    public function test_sidebar_contains_keyboard_shortcuts(): void
    {
        $content = (new Filesystem())->get(resource_path('views/components/sidebar.blade.php'));

        $this->assertStringContainsString('x-on:keydown.ctrl.d', $content);
        $this->assertStringContainsString('Ctrl+D', $content);
        $this->assertStringContainsString('x-on:keydown.ctrl.t', $content);
        $this->assertStringContainsString('Ctrl+T', $content);
    }
}
