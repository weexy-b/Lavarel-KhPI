<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    /**
     * Поля для масового заповнення.
     */
    protected $fillable = [
        'task_id',
        'author_id',
        'body'
    ];


    /**
     * Задача, до якої належить цей коментар.
     * Зв'язок: Belongs To Task.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Автор коментаря (Користувач).
     * Вказуємо 'author_id', щоб Laravel знав, яку колонку використовувати для зв'язку.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
