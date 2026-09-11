<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTaskAssigneeRequest;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class TaskAssignmentController extends Controller
{
    /**
     * Assign the specified task to a member (or unassign it).
     */
    public function update(UpdateTaskAssigneeRequest $request, TaskList $taskList, Task $task): JsonResponse
    {
        if ($task->task_list_id !== $taskList->id) {
            abort(404);
        }

        Gate::authorize('assign', $task);

        $assigneeId = $request->validated()['assignee_id'] ?? null;
        $previousAssigneeId = $task->assignee_id;

        if ($assigneeId !== null) {
            $assignee = User::findOrFail($assigneeId);

            if (! $assignee->isActive() || ! $taskList->isMember($assignee)) {
                return response()->json(['message' => 'Hanya member aktif proyek ini yang dapat ditugaskan.'], 422);
            }
        }

        $task->assignee_id = $assigneeId;
        $task->save();

        if ($assigneeId !== null && (int) $assigneeId !== (int) $previousAssigneeId) {
            $task->assignee->notify(new TaskAssigned($task->refresh(), $request->user()));
        }

        return response()->json([
            'message' => $assigneeId === null ? 'Assignment task dihapus.' : 'Task berhasil ditugaskan.',
            'data' => $task->load('assignee'),
        ]);
    }
}
