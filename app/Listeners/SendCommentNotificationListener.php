<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Jobs\SendTelegramMessageJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCommentNotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        $comment = $event->comment;
        // Отримуємо задачу, до якої належить коментар (через релейшн task())
        $task = $comment->task;
        // Отримуємо автора коментаря (через релейшн author())
        $authorName = $comment->author ? $comment->author->name : 'Unknown User';

        // Формуємо текст повідомлення для Telegram
        $message = "💬 <b>Новий коментар!</b>\n\n" .
            "📌 <b>Задача:</b> {$task->title}\n" .
            "👤 <b>Автор:</b> {$authorName}\n" .
            "📝 <b>Текст:</b> <i>{$comment->body}</i>\n\n" .
            "🕒 <b>Час:</b> {$comment->created_at->format('d.m.Y H:i')}";

        // Ставимо відправку в чергу
        SendTelegramMessageJob::dispatch($message);
    }
}
