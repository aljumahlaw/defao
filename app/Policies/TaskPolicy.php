<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view task list (filtered by visibleTo)
    }

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        return $task->user_id === $user->id || $task->assignee_id === $user->id;
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create tasks
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        // Creator or assignee can update
        return $task->user_id === $user->id || $task->assignee_id === $user->id;
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only creator can delete
        return $task->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the task.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->isAdmin() || $task->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the task.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->isAdmin();
    }
}

