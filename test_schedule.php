<?php

// Define the base path to the application
define('LARAVEL_START', microtime(true));

// Register the Composer autoloader
require __DIR__.'/vendor/autoload.php';

// Load the app
$app = require_once __DIR__.'/bootstrap/app.php';

// Get the kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Set the application timezone
date_default_timezone_set('Asia/Jakarta');

echo "Current time: " . date('Y-m-d H:i:s') . PHP_EOL;
echo "Attempting to run scheduler..." . PHP_EOL;

// Try to run the schedule manually
$schedule = $app->make(Illuminate\Console\Scheduling\Schedule::class);

// Define the schedule
$schedulerKernel = new App\Console\Kernel($app, $app->make(Illuminate\Contracts\Events\Dispatcher::class));
$method = new ReflectionMethod($schedulerKernel, 'schedule');
$method->setAccessible(true);
$method->invoke($schedulerKernel, $schedule);

// Check all the events
$events = $schedule->events();
echo "Found " . count($events) . " scheduled events." . PHP_EOL;

foreach ($events as $event) {
    echo "Event: " . get_class($event) . PHP_EOL;
    echo "Command: " . $event->command . PHP_EOL;
    echo "Expression: " . $event->expression . PHP_EOL;
    
    // Check if due
    $due = $event->isDue($app);
    echo "Is due: " . ($due ? 'Yes' : 'No') . PHP_EOL;
    
    if ($due) {
        echo "Running the event..." . PHP_EOL;
        $event->run($app);
    }
    
    echo "----------------------------" . PHP_EOL;
}

echo "Done checking scheduler." . PHP_EOL; 