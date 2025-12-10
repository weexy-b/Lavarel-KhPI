<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Jobs\SendTelegramMessageJob;
use Carbon\Carbon;

class CheckOverdueTasks extends Command
{
    /**
     * Ім'я команди для запуску.
     */
    protected $signature = 'app:check-overdue-tasks';

    /**
     * Опис команди.
     */
    protected $description = 'Check for in_progress tasks older than 7 days and mark them as expired';

    public function handle()
    {
        $this->info('Checking for overdue tasks...');

        //  Шукаємо задачі: статус 'in_progress' І updated_at старіше 7 днів
        $overdueTasks = Task::where('status', 'in_progress')
            ->where('updated_at', '<', Carbon::now()->subDays(7))
            ->get();

        if ($overdueTasks->isEmpty()) {
            $this->info('No overdue tasks found.');
            return;
        }

        foreach ($overdueTasks as $task) {
            // Оновлюємо статус
            $task->update(['status' => 'expired']);

            $this->info("Task ID {$task->id} marked as expired.");

            //  Відправляємо в Telegram
            $message = "⚠️ <b>Увага! Задача прострочена!</b>\n\n" .
                "📌 <b>Назва:</b> {$task->title}\n" .
                "📅 <b>Була оновлена:</b> {$task->updated_at->format('d.m.Y')}\n" .
                "🔴 <b>Новий статус:</b> EXPIRED";

            SendTelegramMessageJob::dispatch($message);
        }

        $this->info("Processed {$overdueTasks->count()} tasks.");
    }
}
