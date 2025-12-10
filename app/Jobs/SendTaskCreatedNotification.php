<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // Для запису в лог

class SendTaskCreatedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;

    /**
     * Приймаємо дані про задачу.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Логіка, яка виконується воркером.
     */
    public function handle(): void
    {
        // Імітація важкої роботи
        sleep(5);

        // Записуємо в файл логів (storage/logs/laravel.log)
        Log::info("[NOTIFICATION SENT] New task created: '{$this->task->title}' (ID: {$this->task->id})");
    }
}
