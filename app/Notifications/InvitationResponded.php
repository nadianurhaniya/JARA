<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvitationResponded extends Notification
{
    use Queueable;

    public function __construct(
        public Invitation $invitation,
    ) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $accepted = $this->invitation->status === Invitation::ACCEPTED;
        $verb = $accepted ? 'menerima' : 'menolak';

        return [
            'message' => "{$this->invitation->email} {$verb} undangan ke \"{$this->invitation->taskList->name}\"",
            'task_list_id' => $this->invitation->task_list_id,
            'task_list_name' => $this->invitation->taskList->name,
            'invitation_id' => $this->invitation->id,
            'status' => $this->invitation->status,
        ];
    }
}
