<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendTaskCreatedNotification;

class TaskController extends Controller
{
    // Отримати задачі конкретного проєкту (з фільтрацією)
    public function indexByProject(Request $request, Project $project)
    {
        $query = $project->tasks();

        // Фільтрація
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('assignee_id')) {
            $query->where('assignee_id', $request->assignee_id);
        }

        return response()->json($query->get());
    }

    // Створити задачу в проєкті
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'in:todo,in_progress,done',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $task = $project->tasks()->create([
            ...$validated,
            'author_id' => Auth::id(),
        ]);

        //SendTaskCreatedNotification::dispatch($task);
        \App\Events\TaskCreated::dispatch($task);

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json($task->load('comments'));
    }

    public function update(Request $request, Task $task)
    {
        // Перевірка: тільки автор або власник проєкту
        if (Auth::id() !== $task->author_id && Auth::id() !== $task->project->owner_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $task->update($request->all());
        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        if (Auth::id() !== $task->author_id && Auth::id() !== $task->project->owner_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
