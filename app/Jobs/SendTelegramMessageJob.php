<?php

namespace App\Jobs;

use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTelegramMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $message;
    protected ?string $chatId;

    /**
     * Приймаємо текст повідомлення.
     */
    public function __construct(string $message, ?string $chatId = null)
    {
        $this->message = $message;
        $this->chatId = $chatId;
    }

    /**
     * Метод handle автоматично отримує сервіс через Dependency Injection
     */
    public function handle(TelegramService $telegramService): void
    {
        $telegramService->sendMessage($this->message, $this->chatId);
    }
}
