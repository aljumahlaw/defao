<?php

namespace Tests\Feature;

use App\Livewire\Documents\DocumentDetail;
use App\Models\Document;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentDetailPDFTest extends TestCase
{
    public function test_open_pdf_modal_dispatches_event_and_button_visible(): void
    {
        $user = User::factory()->create();

        $document = Document::factory()->create([
            'user_id' => $user->id,
            'assignee_id' => $user->id,
        ]);

        $this->actingAs($user);

        Livewire::test(DocumentDetail::class, ['documentId' => $document->id])
            ->assertSee('👁️ عرض PDF')
            ->call('openPdfModal')
            ->assertDispatched('open-pdf-modal');
    }
}
