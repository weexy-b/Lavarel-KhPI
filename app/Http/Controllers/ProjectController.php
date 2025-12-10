<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Список проєктів користувача.
     * Повертає і ті, де він власник, і ті, де він учасник.
     */
    public function index()
    {
        $user = Auth::user();

        return response()->json([
            'owned_projects' => $user->ownedProjects, // Проєкти, які створив я
            'member_projects' => $user->projects,     // Проєкти, де я учасник
        ]);
    }

    /**
     * Створення нового проєкту.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Створюємо проєкт і автоматично ставимо owner_id поточного юзера
        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'owner_id' => Auth::id(),
        ]);

        return response()->json($project, 201);
    }

    /**
     * Перегляд конкретного проєкту.
     * Доступ сюди вже перевірено через Middleware 'project.access'.
     */
    public function show(Project $project)
    {
        // Завантажуємо також учасників та задачі для зручності
        return response()->json($project->load('members', 'tasks'));
    }

    /**
     * Оновлення проєкту.
     * Редагувати може тільки власник.
     * Middleware перевіряє лише участь, тому тут потрібна додаткова перевірка.
     */
    public function update(Request $request, Project $project)
    {
        if ($project->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Only the owner can update this project'], 403);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    /**
     * Видалення проєкту.
     * Видаляти може тільки власник.
     */
    public function destroy(Project $project)
    {
        if ($project->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Only the owner can delete this project'], 403);
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
