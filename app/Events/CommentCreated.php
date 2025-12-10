<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class CommentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Сюди ми запишемо створений коментар, щоб передати його далі.
     */
    public $comment;

    /**
     * Create a new event instance.
     * Приймаємо модель Comment у конструкторі.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
