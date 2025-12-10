<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Дозволяємо записувати всі поля
    protected $guarded = [];

    // Автоматично перетворюємо JSON з бази в масив PHP і навпаки
    protected $casts = [
        'payload' => 'array',
    ];
}
