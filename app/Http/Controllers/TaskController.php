<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');

        $tasks = $request->user()->tasks()
            ->when($filter === 'active', fn ($query) => $query->where('completed', false))
            ->when($filter === 'completed', fn ($query) => $query->where('completed', true))
            ->latest()
            ->get();

        return view('tasks.index', ['tasks' => $tasks, 'filter' => $filter]);
    }

    public function create(): View
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $request->user()->tasks()->create($request->validated());

        return redirect()->route('tasks.index')->with('status', 'Task created.');
    }

    public function edit(Request $request, Task $task): View
    {
        $this->ensureOwner($request, $task);

        return view('tasks.edit', ['task' => $task]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->ensureOwner($request, $task);

        $task->update($request->validated());

        return redirect()->route('tasks.index')->with('status', 'Task updated.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $this->ensureOwner($request, $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('status', 'Task deleted.');
    }

    public function toggle(Request $request, Task $task): RedirectResponse
    {
        $this->ensureOwner($request, $task);

        $task->update(['completed' => ! $task->completed]);

        return redirect()->back();
    }

    private function ensureOwner(Request $request, Task $task): void
    {
        abort_unless($task->user_id === $request->user()->id, 403);
    }
}
