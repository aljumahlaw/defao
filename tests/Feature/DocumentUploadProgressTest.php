<?php

namespace Tests\Feature;

use Illuminate\Filesystem\Filesystem;
use Tests\TestCase;

class DocumentUploadProgressTest extends TestCase
{
    public function test_progress_markup_exists_in_upload_view(): void
    {
        $filesystem = new Filesystem();
        $content = $filesystem->get(resource_path('views/livewire/documents/document-upload.blade.php'));

        $this->assertStringContainsString('wire:model="file"', $content);
        $this->assertStringContainsString('جاري الرفع', $content);
        $this->assertStringContainsString('uploadProgress?.progress', $content);
    }
}
