<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendWhatsAppReminder;
use App\Console\Commands\TestScheduler;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Mengganti cron expression langsung
        $task = $schedule->command('pengingat:whatsapp')
                // Jadwalkan pada jam 8:00 pagi setiap hari
                ->cron('00 08 * * *')
                ->timezone('Asia/Jakarta')
                ->appendOutputTo(storage_path('logs/whatsapp-schedule.log'));
        
        // Catat cron expression yang digunakan
        file_put_contents(
            storage_path('logs/cron-debug.log'),
            date('Y-m-d H:i:s') . " Cron expression: " . $task->expression . PHP_EOL,
            FILE_APPEND
        );

        // Command test untuk debugging
        $schedule->command('test:scheduler')
                ->everyMinute()
                ->appendOutputTo(storage_path('logs/test-scheduler.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
