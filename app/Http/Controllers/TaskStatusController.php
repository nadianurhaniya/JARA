<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class TaskStatusController extends Controller
{
    /**
     * Change the completion status of the specified task.
     * Owners may change any task; members only tasks assigned to them.
     */
    public function update(UpdateTaskStatusRequest $request, TaskList $taskList, Task $task): JsonResponse
    {
        if ($task->task_list_id !== $taskList->id) {
            abort(404);
        }

        Gate::authorize('updateStatus', $task);

        $task->is_completed = $request->validated()['is_completed'];
        $task->completed_at = $task->is_completed ? now() : null;
        $task->save();

        return response()->json([
            'message' => $task->is_completed ? 'Task ditandai selesai.' : 'Task ditandai belum selesai.',
            'data' => $task,
        ]);
    }
}
