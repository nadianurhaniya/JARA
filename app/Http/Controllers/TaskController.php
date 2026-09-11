<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TaskList $taskList): View
    {
        Gate::authorize('view', $taskList);

        $sort = $request->string('sort')->value();
        $direction = $request->string('direction', 'asc')->value() === 'desc' ? 'desc' : 'asc';
        $status = $request->string('status', 'all')->value();

        $query = $taskList->tasks();

        $query->when($status === 'completed', fn ($q) => $q->completed())
            ->when($status === 'incomplete', fn ($q) => $q->incomplete());

        if ($sort === 'due_date') {
            $query->sortByDueDate($direction)->sortByPriority();
        } else {
            $query->sortByPriority($direction)->sortByDueDate();
        }

        $tasks = $query->paginate(15)->withQueryString();

        return view('tasks.index', [
            'taskList' => $taskList,
            'tasks' => $tasks,
            'sort' => $sort,
            'direction' => $direction,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TaskList $taskList): View
    {
        Gate::authorize('update', $taskList);

        return view('tasks.create', ['taskList' => $taskList]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, TaskList $taskList): RedirectResponse
    {
        $task = $taskList->tasks()->make($request->validated());
        $task->user_id = $request->user()->id;
        $task->save();

        return redirect()->route('task-lists.tasks.index', $taskList)->with('status', 'Tugas berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $taskList, Task $task): View
    {
        Gate::authorize('update', $task);

        $task->load('subtasks');

        return view('tasks.edit', ['taskList' => $taskList, 'task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, TaskList $taskList, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->route('task-lists.tasks.index', $taskList)->with('status', 'Tugas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $taskList, Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()->route('task-lists.tasks.index', $taskList)->with('status', 'Tugas berhasil dihapus.');
    }

    /**
     * Toggle the completion status of the specified task.
     */
    public function toggleComplete(TaskList $taskList, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $task->is_completed = ! $task->is_completed;
        $task->completed_at = $task->is_completed ? now() : null;
        $task->save();

        return redirect()->route('task-lists.tasks.index', $taskList);
    }
}
