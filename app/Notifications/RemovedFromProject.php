<?php

namespace App\Notifications;

use App\Models\TaskList;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RemovedFromProject extends Notification
{
    use Queueable;

    public function __construct(
        public TaskList $taskList,
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
            'message' => "Kamu dihapus dari proyek \"{$this->taskList->name}\"",
            'task_list_id' => $this->taskList->id,
            'task_list_name' => $this->taskList->name,
        ];
    }
}
