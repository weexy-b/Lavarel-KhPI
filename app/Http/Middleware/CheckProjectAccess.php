<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Project;

class CheckProjectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Отримуємо параметр маршруту.
        // В роутах ми можемо назвати його 'project' або 'id', тому перевіряємо обидва варіанти.
        $projectParam = $request->route('project') ?? $request->route('id');

        // Якщо ID не передано в маршруті, пропускаємо (або можна видавати помилку)
        if (!$projectParam) {
            return $next($request);
        }

        // Отримуємо сам об'єкт Проєкту
        // Якщо Laravel вже знайшов модель (Route Model Binding), то $projectParam буде об'єктом.
        // Якщо ні - це просто число (ID), і треба знайти проєкт вручну.
        if ($projectParam instanceof Project) {
            $project = $projectParam;
        } else {
            $project = Project::find($projectParam);
        }

        // Якщо проєкт не знайдено повертаємо 404
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // Отримуємо поточного авторизованого користувача
        $user = $request->user();

        // Перевірка доступу
        // Чи є він власником
        $isOwner = $project->owner_id === $user->id;

        //  Чи є він у списку учасників
        // Використовуємо exists(), щоб не завантажувати всіх учасників, а просто перевірити наявність.
        $isMember = $project->members()->where('user_id', $user->id)->exists();

        if (!$isOwner && !$isMember) {
            return response()->json(['message' => 'Access denied. You are not a member of this project.'], 403);
        }

        return $next($request);
    }
}
