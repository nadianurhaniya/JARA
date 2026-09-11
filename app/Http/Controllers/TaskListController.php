<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use App\Models\TaskList;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $taskLists = $request->user()
            ->taskLists()
            ->withCount('tasks')
            ->latest()
            ->get();

        return view('task-lists.index', ['taskLists' => $taskLists]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('task-lists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskListRequest $request): RedirectResponse
    {
        $request->user()->taskLists()->create($request->validated());

        return redirect()->route('task-lists.index')->with('status', 'Daftar/proyek berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $taskList): View
    {
        Gate::authorize('update', $taskList);

        return view('task-lists.edit', ['taskList' => $taskList]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskListRequest $request, TaskList $taskList): RedirectResponse
    {
        $taskList->update($request->validated());

        return redirect()->route('task-lists.index')->with('status', 'Daftar/proyek berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $taskList): RedirectResponse
    {
        Gate::authorize('delete', $taskList);

        $taskList->delete();

        return redirect()->route('task-lists.index')->with('status', 'Daftar/proyek berhasil dihapus.');
    }
}
