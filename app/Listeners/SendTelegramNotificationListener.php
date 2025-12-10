<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Jobs\SendTelegramMessageJob;

class SendTelegramNotificationListener
{
    public function handle(TaskCreated $event): void
    {
        $task = $event->task;

        // Формуємо повідомлення
        $message = "✅ <b>New Task Created!</b>\n\n" .
            "📌 <b>Title:</b> {$task->title}\n" .
            "📊 <b>Status:</b> {$task->status}\n" .
            "📅 <b>Created at:</b> {$task->created_at}";

        // Ставимо джобу в чергу
        SendTelegramMessageJob::dispatch($message);
    }
}
