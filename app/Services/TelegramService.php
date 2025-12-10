<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $token;
    protected string $chatId;

    public function __construct()
    {
        $this->token = config('services.telegram.token');
        $this->chatId = config('services.telegram.chat_id');
    }

    public function sendMessage(string $message, ?string $chatId = null): void
    {
        $chatId = $chatId ?? $this->chatId; // Якщо ID не передано, беремо з конфігу

        if (!$this->token || !$chatId) {
            Log::warning('Telegram credentials not set.');
            return;
        }

        try {
            // Відправляємо POST запит на API Telegram
            $response = Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML', // Дозволяє використовувати жирний шрифт тощо
            ]);

            if ($response->successful()) {
                Log::info("Telegram message sent to {$chatId}");
            } else {
                Log::error("Telegram API Error: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Telegram Service Exception: " . $e->getMessage());
        }
    }
}
