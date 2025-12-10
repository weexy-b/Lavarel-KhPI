<?php

namespace App\Events;

use App\Models\Task; // Імпорт моделі
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated
{
    use Dispatchable, SerializesModels;

    public $task;

    /**
     * Приймаємо створену задачу.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}
