<?php

namespace App\Http\Controllers;

use App\Http\Requests\RespondInvitationRequest;
use App\Http\Requests\StoreInvitationRequest;
use App\Models\Invitation;
use App\Models\TaskList;
use App\Models\User;
use App\Notifications\InvitationResponded;
use App\Notifications\MemberInvited;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class InvitationController extends Controller
{
    /**
     * Invite a user to the specified project.
     */
    public function store(StoreInvitationRequest $request, TaskList $taskList): JsonResponse
    {
        Gate::authorize('inviteMember', $taskList);

        $email = strtolower(trim($request->validated()['email']));

        if ($email === strtolower($request->user()->email)) {
            return response()->json(['message' => 'Tidak dapat mengundang diri sendiri.'], 422);
        }

        $invitedUser = User::where('email', $email)->first();

        if ($invitedUser && $taskList->isMember($invitedUser)) {
            return response()->json(['message' => 'User tersebut sudah menjadi member proyek ini.'], 422);
        }

        $duplicate = $taskList->invitations()
            ->where('email', $email)
            ->where('status', Invitation::PENDING)
            ->exists();

        if ($duplicate) {
            return response()->json(['message' => 'User tersebut sudah memiliki undangan pending.'], 422);
        }

        $invitation = $taskList->invitations()->create([
            'user_id' => $invitedUser?->id,
            'email' => $email,
            'status' => Invitation::PENDING,
        ]);

        $invitedUser?->notify(new MemberInvited($taskList, $request->user()));

        return response()->json([
            'message' => "Undangan dikirim ke {$email}.",
            'data' => $invitation,
        ], 201);
    }

    /**
     * Accept or reject the specified invitation.
     */
    public function update(RespondInvitationRequest $request, Invitation $invitation): JsonResponse
    {
        Gate::authorize('respond', $invitation);

        if (! $invitation->isPending()) {
            return response()->json(['message' => 'Undangan ini sudah diproses sebelumnya.'], 422);
        }

        $accept = $request->validated()['action'] === 'accept';

        $invitation->status = $accept ? Invitation::ACCEPTED : Invitation::REJECTED;
        $invitation->save();

        if ($accept) {
            $invitation->taskList->members()->syncWithoutDetaching([
                $invitation->user_id => ['role' => 'member'],
            ]);
        }

        $invitation->taskList->owner->notify(new InvitationResponded($invitation->refresh()));

        $verb = $accept ? 'diterima' : 'ditolak';

        return response()->json([
            'message' => "Undangan {$verb}.",
            'data' => $invitation,
        ]);
    }

    /**
     * Cancel a pending invitation.
     */
    public function destroy(TaskList $taskList, Invitation $invitation): JsonResponse
    {
        if ($invitation->task_list_id !== $taskList->id) {
            abort(404);
        }

        Gate::authorize('cancel', $invitation);

        $invitation->delete();

        return response()->json(['message' => 'Undangan dibatalkan.']);
    }
}
