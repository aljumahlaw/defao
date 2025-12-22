<?php

namespace Tests\Feature;

use App\Livewire\Documents\DocumentTable;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class DocumentPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_or_assigned_document(): void
    {
        $owner = User::factory()->create();
        $assignee = new User(['id' => 9999]);
        $other = User::factory()->create();

        $document = Document::factory()->create([
            'user_id' => $owner->id,
            'assignee_id' => $assignee->id,
        ]);

        $this->assertTrue(Gate::forUser($owner)->allows('view', $document));
        $this->assertTrue(Gate::forUser($assignee)->allows('view', $document));
        $this->assertFalse(Gate::forUser($other)->allows('view', $document));
    }

    public function test_only_assignee_can_update_document(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $other = User::factory()->create();

        $document = Document::factory()->create([
            'user_id' => $owner->id,
            'assignee_id' => $assignee->id,
        ]);

        $this->assertTrue(Gate::forUser($assignee)->allows('update', $document));
        $this->assertFalse(Gate::forUser($owner)->allows('update', $document));
        $this->assertFalse(Gate::forUser($other)->allows('update', $document));
    }

    public function test_document_table_scopes_documents_to_current_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // مستندات تخص المستخدم الحالي (كمنشئ أو مكلّف)
        $ownedDoc = Document::factory()->create(['user_id' => $user->id]);
        $assignedDoc = Document::factory()->create(['assignee_id' => $user->id]);

        // مستند لا يخص المستخدم الحالي
        $otherDoc = Document::factory()->create([
            'user_id' => $otherUser->id,
            'assignee_id' => $otherUser->id,
        ]);

        $this->actingAs($user);

        /** @var \App\Livewire\Documents\DocumentTable $component */
        $component = app(DocumentTable::class);

        $paginator = $component->documents();
        $ids = collect($paginator->items())->pluck('id')->all();

        $this->assertContains($ownedDoc->id, $ids);
        $this->assertContains($assignedDoc->id, $ids);
        $this->assertNotContains($otherDoc->id, $ids);
    }
}

