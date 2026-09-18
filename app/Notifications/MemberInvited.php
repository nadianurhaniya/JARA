<?php

namespace App\Notifications;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MemberInvited extends Notification
{
    use Queueable;

    public function __construct(
        public TaskList $taskList,
        public User $inviter,
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
        return [
            'message' => "Kamu diundang oleh {$this->inviter->name} untuk bergabung ke \"{$this->taskList->name}\"",
            'task_list_id' => $this->taskList->id,
            'task_list_name' => $this->taskList->name,
            'inviter_id' => $this->inviter->id,
            'inviter_name' => $this->inviter->name,
        ];
    }
}
