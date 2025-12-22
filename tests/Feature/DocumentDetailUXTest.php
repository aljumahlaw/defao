<?php

namespace Tests\Feature;

use App\Livewire\Documents\DocumentDetail;
use App\Models\Document;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentDetailUXTest extends TestCase
{
    public function test_approve_shows_loading_state_and_emits_toast(): void
    {
        $user = User::factory()->create();

        $document = Document::factory()->create([
            'user_id' => $user->id,
            'assignee_id' => $user->id,
            'current_stage' => 'draft',
        ]);

        $this->actingAs($user);

        Livewire::test(DocumentDetail::class, ['documentId' => $document->id])
            ->assertSee('✅ موافقة')
            ->call('approve')
            ->assertDispatched('show-toast');
    }
}
