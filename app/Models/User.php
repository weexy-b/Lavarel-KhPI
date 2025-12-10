<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Проєкти, де користувач є ВЛАСНИКОМ (One-to-Many).
     * Ми шукаємо в таблиці projects записи, де owner_id = id цього юзера.
     */
    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    /**
     * Проєкти, де користувач є УЧАСНИКОМ (Many-to-Many).
     * Це зв'язок через проміжну таблицю project_user.
     * withPivot('role') дозволяє отримати роль (наприклад, 'member' або 'admin').
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withPivot('role');
    }

    /**
     * Задачі, які ПРИЗНАЧЕНІ цьому користувачеві.
     * Шукаємо в tasks, де assignee_id = id цього юзера.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    /**
     * Задачі, які СТВОРИВ цей користувач (автор).
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'author_id');
    }

    /**
     * Коментарі, які залишив цей користувач.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }
}
