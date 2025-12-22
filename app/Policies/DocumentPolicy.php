<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Document $document): bool
    {
        if ($user->isAdmin()) return true;
        
        if ($user->isLawyer()) {
            return $document->user_id === $user->id 
                || $document->assignee_id === $user->id;
        }
        
        if ($user->isAssistant()) {
            return $document->assignee_id === $user->id;
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Document $document): bool
    {
        return $this->view($user, $document);
    }

    public function delete(User $user, Document $document): bool
    {
        if ($user->isAssistant()) return false;
        return $user->isAdmin() || $document->user_id === $user->id;
    }

    public function forceDelete(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }

    public function archive(User $user, Document $document): bool
    {
        if ($user->isAssistant()) return false;
        return $user->isAdmin() || $document->user_id === $user->id;
    }
}
