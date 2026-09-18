<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy
{
    /**
     * Determine whether the user can respond to the invitation.
     * Only the invited user may respond, and only once.
     */
    public function respond(User $user, Invitation $invitation): bool
    {
        return $invitation->isPending()
            && $invitation->user_id !== null
            && $invitation->user_id === $user->id;
    }

    /**
     * Determine whether the user can cancel the invitation.
     */
    public function cancel(User $user, Invitation $invitation): bool
    {
        return $invitation->isPending() && $invitation->taskList->isOwner($user);
    }
}
