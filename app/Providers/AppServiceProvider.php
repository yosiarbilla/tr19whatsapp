<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\TestScheduler;
use App\Console\Commands\SendWhatsAppReminder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->resolving(Schedule::class, function ($schedule) {
            // Command test untuk debugging scheduler
            $schedule->command('test:scheduler')->everyMinute();
            
            // Command WhatsApp reminder
            $schedule->command('pengingat:whatsapp')->dailyAt('08:00')->timezone('Asia/Jakarta');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set timezone default ke Asia/Jakarta
        config(['app.timezone' => 'Asia/Jakarta']);
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setLocale('id');
    }
}
