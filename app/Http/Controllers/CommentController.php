<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\CommentCreated;

class CommentController extends Controller
{
    /**
     * Отримати всі коментарі до задачі
     */
    public function index(Task $task)
    {
        return response()->json($task->comments()->with('author:id,name')->get());
    }

    /**
     * Створити новий коментар
     */
    public function store(Request $request, Task $task)
    {
        $validated = $request->validate(['body' => 'required|string']);

        $comment = $task->comments()->create([
            'body' => $validated['body'],
            'author_id' => Auth::id()
        ]);

        CommentCreated::dispatch($comment);

        return response()->json($comment, 201);
    }

    /**
     * Видалити коментар
     */
    public function destroy(Comment $comment)
    {
        if ($comment->author_id !== Auth::id()) {
            return response()->json(['message' => 'You can only delete your own comments'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
