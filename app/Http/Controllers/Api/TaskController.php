<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Jobs\SendTaskNotification;

class TaskController extends Controller
{
    public function index(Request $request, Team $team): JsonResponse
    {
        $tasks = $team->tasks()
            ->with('assignee:id,name,email')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->priority, fn($q, $p) => $q->where('priority', $p))
            ->orderBy('due_at')
            ->paginate(20);

        return response()->json($tasks);
    }

    public function store(Request $request, Team $team): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_at' => 'nullable|date|after:now',
        ]);

        $task = $team->tasks()->create([
            ...$data,
            'created_by' => $request->user()->id,
        ]);
        $task = $team->tasks()->create([
            ...$data,
            'created_by' => $request->user()->id,
        ]);

        if ($task->assigned_to) {
            SendTaskNotification::dispatch($task->load('assignee'));
        }
        return response()->json($task->load('assignee:id,name,email'), 201);
    }

    public function show(Team $team, Task $task): JsonResponse
    {
        abort_if($task->team_id !== $team->id, 404);

        return response()->json($task->load('assignee:id,name,email', 'creator:id,name,email'));
    }

    public function update(Request $request, Team $team, Task $task): JsonResponse
    {
        abort_if($task->team_id !== $team->id, 404);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:todo,in_progress,done',
            'priority' => 'sometimes|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_at' => 'nullable|date',
        ]);

        $task->update($data);

        return response()->json($task->fresh('assignee:id,name,email'));
    }

    public function destroy(Team $team, Task $task): JsonResponse
    {
        abort_if($task->team_id !== $team->id, 404);

        $task->delete();

        return response()->json(null, 204);
    }
}