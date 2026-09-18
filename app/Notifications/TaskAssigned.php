<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    public function __construct(
        public Task $task,
        public User $assigner,
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
            'message' => "Task \"{$this->task->title}\" ditugaskan kepadamu oleh {$this->assigner->name}",
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'task_list_id' => $this->task->task_list_id,
            'assigner_id' => $this->assigner->id,
            'assigner_name' => $this->assigner->name,
        ];
    }
}
