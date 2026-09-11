<?php

namespace App\Policies;

use App\Models\TaskList;
use App\Models\User;

class TaskListPolicy
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
    public function view(User $user, TaskList $taskList): bool
    {
        return $user->id === $taskList->user_id;
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
    public function update(User $user, TaskList $taskList): bool
    {
        return $user->id === $taskList->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TaskList $taskList): bool
    {
        return $user->id === $taskList->user_id;
    }

    /**
     * Determine whether the user can view the collaboration area
     * (members, invitations, assignments) of the model.
     */
    public function viewCollaboration(User $user, TaskList $taskList): bool
    {
        return $taskList->isOwner($user) || $taskList->isMember($user);
    }

    /**
     * Determine whether the user can invite members to the model.
     */
    public function inviteMember(User $user, TaskList $taskList): bool
    {
        return $taskList->isOwner($user);
    }

    /**
     * Determine whether the user can remove members from the model.
     */
    public function removeMember(User $user, TaskList $taskList): bool
    {
        return $taskList->isOwner($user);
    }
}
