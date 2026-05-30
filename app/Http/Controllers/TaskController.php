<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(Request $request): Response
    {
        $team = Team::with('members:id,name,email')->first();

        $tasks = $team->tasks()
            ->with('assignee:id,name,email')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
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
        $team = Team::first();

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'priority'    => 'in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_at'      => 'nullable|date',
        ]);

        $team->tasks()->create([
            ...$data,
            'created_by' => $team->owner_id,
        ]);

        return redirect()->back();
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update($data);

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->back();
    }
}