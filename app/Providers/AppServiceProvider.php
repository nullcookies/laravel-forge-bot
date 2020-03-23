<?php

namespace App\Providers;

use App\Contracts\LaravelForgeContract;
use App\Contracts\TelegramBotContract;
use App\Integrations\Laravel\Forge\ThemsaidLaravelForge;
use App\Integrations\Telegram\IrazasyedTelegramBot;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IrazasyedTelegramBot::class, function () {
            return new IrazasyedTelegramBot(config('services.telegram.bot.api_key'));
        });

        $this->app->bind(TelegramBotContract::class, IrazasyedTelegramBot::class);
        $this->app->bind(LaravelForgeContract::class, ThemsaidLaravelForge::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
