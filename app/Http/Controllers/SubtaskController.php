<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Requests\UpdateSubtaskRequest;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class SubtaskController extends Controller
{
    /**
     * Store a newly created subtask for the given task.
     */
    public function store(StoreSubtaskRequest $request, Task $task): RedirectResponse
    {
        $subtask = $task->subtasks()->make($request->validated());
        $subtask->position = $task->subtasks()->count();
        $subtask->save();

        return redirect()->route('task-lists.tasks.edit', [$task->task_list_id, $task]);
    }

    /**
     * Update the specified subtask.
     */
    public function update(UpdateSubtaskRequest $request, Subtask $subtask): RedirectResponse
    {
        $subtask->update($request->validated());

        return redirect()->route('task-lists.tasks.edit', [$subtask->task->task_list_id, $subtask->task]);
    }

    /**
     * Toggle the completion status of the specified subtask.
     */
    public function toggleComplete(Subtask $subtask): RedirectResponse
    {
        Gate::authorize('update', $subtask->task);

        $subtask->is_completed = ! $subtask->is_completed;
        $subtask->save();

        return redirect()->route('task-lists.tasks.edit', [$subtask->task->task_list_id, $subtask->task]);
    }

    /**
     * Remove the specified subtask.
     */
    public function destroy(Subtask $subtask): RedirectResponse
    {
        Gate::authorize('update', $subtask->task);

        $taskListId = $subtask->task->task_list_id;
        $task = $subtask->task;

        $subtask->delete();

        return redirect()->route('task-lists.tasks.edit', [$taskListId, $task]);
    }
}
