<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

// Імпортуємо Події
use App\Events\TaskCreated;
use App\Events\CommentCreated;

// Імпортуємо Слухачів
use App\Listeners\SendTelegramNotificationListener;
use App\Listeners\SendCommentNotificationListener;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        //  Реєструємо слухача для створення задачі
//        Event::listen(
//            TaskCreated::class,
//            SendTelegramNotificationListener::class,
//        );
//
//        // Реєструємо слухача для створення коментаря
//        Event::listen(
//            CommentCreated::class,
//            SendCommentNotificationListener::class,
//        );
    }
}
