<?php

/**
 * CRON JOB EXTERNAL FILE
 * 
 * File ini dapat diakses langsung oleh cron job hosting
 * 
 * Contoh penggunaan di cPanel:
 * /usr/local/bin/php /home/username/public_html/direktori_cron.php
 * 
 * Atau di shared hosting:
 * php -q /home/username/public_html/direktori_cron.php
 */

// Bootstrap aplikasi
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Tampilkan waktu eksekusi
echo "===== MEMULAI CRON PADA " . date('Y-m-d H:i:s') . " =====\n";

// Jalankan command pengingat WhatsApp
try {
    echo "Menjalankan pengingat WhatsApp...\n";
    $result = $kernel->call('pengingat:whatsapp');
    echo "Command selesai dengan status: " . ($result === 0 ? 'SUKSES' : 'GAGAL') . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "===== SELESAI PADA " . date('Y-m-d H:i:s') . " =====\n"; 