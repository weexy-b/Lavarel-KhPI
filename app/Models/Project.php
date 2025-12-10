<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    /**
     * Поля, які можна масово заповнювати (важливо для сідерів/фабрик).
     */
    protected $fillable = [
        'owner_id',
        'name',
        'description'
    ];


    /**
     * Власник проєкту.
     * Зв'язок: "Проєкт належить (belongsTo) Користувачу".
     * Ми явно вказуємо 'owner_id', бо це нестандартна назва (стандартна була б user_id).
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Учасники проєкту (команда).
     * Зв'язок: "Проєкт має багато (belongsToMany) Користувачів".
     * Це зв'язок Many-to-Many через таблицю `project_user`.
     */
    public function members()
    {
        // withPivot('role') дозволяє отримати роль учасника в цьому проєкті
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    /**
     * Задачі в цьому проєкті.
     * Зв'язок: "Проєкт має багато Задач".
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
