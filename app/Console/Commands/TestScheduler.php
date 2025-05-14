<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestScheduler extends Command
{
    protected $signature = 'test:scheduler';
    protected $description = 'Command test untuk menguji Laravel Scheduler';

    public function handle()
    {
        $this->info(date('Y-m-d H:i:s') . ' - Test scheduler berhasil dijalankan!');
        
        // Catat ke file log untuk bukti command ini berjalan
        $logPath = storage_path('logs/scheduler_test.log');
        file_put_contents(
            $logPath, 
            date('Y-m-d H:i:s') . ' - Test scheduler berjalan' . PHP_EOL, 
            FILE_APPEND
        );
        
        return 0;
    }
} 