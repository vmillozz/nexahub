<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(Request $request): Response
    {
        $team = auth()->user()->teams()->with('members:id,name,email')->firstOrFail();

        $tasks = $team->tasks()
            ->with('assignee:id,name,email')
            ->when($request->status,   fn($q, $s) => $q->where('status', $s))
            ->when($request->priority, fn($q, $p) => $q->where('priority', $p))
            ->orderBy('due_at')
            ->get();

        return Inertia::render('Tasks/Index', [
            'tasks'   => $tasks,
            'members' => $team->members,
            'filters' => $request->only(['status', 'priority']),
        ]);
    }

    public function store(Request $request)
    {
        $team = auth()->user()->teams()->firstOrFail();

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'priority'    => 'in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_at'      => 'nullable|date',
        ]);

        $team->tasks()->create([
            ...$data,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back();
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update($data);

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->back();
    }
}