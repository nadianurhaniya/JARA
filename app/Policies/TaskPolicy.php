<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can assign the model to a member.
     */
    public function assign(User $user, Task $task): bool
    {
        return $task->taskList->isOwner($user);
    }

    /**
     * Determine whether the user can change the completion status.
     * FR-23: only the assignee may change status (owner is read-only
     * for member tasks, monitoring + assignment only).
     */
    public function updateStatus(User $user, Task $task): bool
    {
        return $task->assignee_id === $user->id && $user->isActive();
    }
}
