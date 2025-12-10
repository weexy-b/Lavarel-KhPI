<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    /**
     * Поля, які можна заповнювати через create() або update().
     */
    protected $fillable = [
        'project_id',
        'author_id',
        'assignee_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date'
    ];

    /**
     * Проєкт, до якого належить ця задача.
     * Зв'язок: Belongs To Project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Автор задачі (Користувач).
     * Ми мусимо явно вказати 'author_id', оскільки Laravel за замовчуванням
     * шукав би 'user_id', а у нас колонка називається інакше.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Виконавець задачі (Користувач), на якого призначено таск.
     * Також вказуємо кастомний ключ 'assignee_id'.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * Коментарі до цієї задачі.
     * Зв'язок: Задача має багато (Has Many) коментарів.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
