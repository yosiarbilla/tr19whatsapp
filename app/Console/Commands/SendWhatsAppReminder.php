<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\BorrowedBook;
use App\Models\MessageLog;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class SendWhatsAppReminder extends Command
{
    protected $signature = 'pengingat:whatsapp';
    protected $description = 'Mengirim pengingat WhatsApp otomatis berdasarkan due date (H-3, H-1, H, H+1, H+3)';

    public function handle()
    {
        $apiKey = config('services.whatsapp.api_key');
        $apiUrl = 'https://wasenderapi.com/api/send-message';

        if (empty($apiKey)) {
            $this->error("❌ WASENDER_API_KEY tidak ditemukan di .env.");
            return;
        }

        // Calculate target dates based on today
        $today = Carbon::today();
        $schedule = [
            'H-2' => $today->copy()->addDays(2),   // 2 days from now (2 days before due date)
            'H-1' => $today->copy()->addDay(),     // tomorrow (1 day before due date)
            'H'   => $today->copy(),               // today (on due date)
            'H+1' => $today->copy()->subDay(),     // yesterday (1 day after due date)
            'H+7' => $today->copy()->subDays(7),   // 7 days ago (7 days after due date)
        ];

        $this->info("🔑 API Key Loaded: " . substr($apiKey, 0, 4) . '' . substr($apiKey, -4));
        $this->info("🌐 Target URL: $apiUrl");

        // Dummy ping to activate session
        $this->info("📡 Sending dummy ping...");
        $dummy = $this->sendWhatsApp($apiKey, $apiUrl, '‪+6281234567890‬', '🔧 WA Session Warm-up');
        if ($dummy->successful()) {
            $this->info("✅ Warm-up successful.");
        } else {
            $this->warn("⚠ Warm-up failed: " . $dummy->status());
        }

        sleep(2); // stabilization delay

        foreach ($schedule as $label => $targetDate) {
            $formattedDate = $targetDate->format('Y-m-d');

            $this->info("📆 Checking for books due on: $formattedDate ($label)");

            $books = BorrowedBook::with('borrower')
                ->whereDate('due_date', $formattedDate)
                ->where('is_returned', false)
                ->get();

            if ($books->isEmpty()) {
                $this->info("ℹ Tidak ada data untuk $label ($formattedDate)");
                continue;
            }

            $this->info("📚 Mengirim pengingat untuk $label - $formattedDate ({$books->count()} data)");

            foreach ($books as $book) {
                $rawPhone = $book->borrower->phone;
                $formattedPhone = $this->formatPhoneNumber($rawPhone);

                // Format the message based on the label
                $daysText = match ($label) {
                    'H-2' => "2 hari",
                    'H-1' => "1 hari",
                    'H'   => "hari ini",
                    'H+1' => "1 hari terlewat",
                    'H+7' => "7 hari terlewat",
                    default => "beberapa hari",
                };

                $dueDate = Carbon::parse($book->due_date)->format('d M Y');
                $indonesianMonths = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                
                $formattedDueDate = Carbon::parse($book->due_date)->format('d F Y');
                foreach ($indonesianMonths as $english => $indonesian) {
                    $formattedDueDate = str_replace($english, $indonesian, $formattedDueDate);
                }

             $message = "📚 Reminder Peminjaman Buku - The Room 19 📖\n\n" . 
                "Halo, Kak {$book->borrower->name}!\n" . 
                
                // Greeting part with more randomization
                Arr::random([
                    "Kami dari tim The Room 19 ingin mengingatkan bahwa",
                    "Tim kami, The Room 19, ingin memberi tahu bahwa",
                    "Dengan senang hati, kami dari The Room 19 mengingatkan bahwa",
                    "Selamat pagi dari tim The Room 19! Kami ingin mengingatkan Anda bahwa",
                    "Halo, kami dari The Room 19 ingin memberitahukan bahwa",
                    "Kami The Room 19 ingin menyampaikan pengingat bahwa",
                    "Kami ingin menginformasikan bahwa, tim The Room 19 mengingatkan Anda tentang",
                ]) . " buku yang Kakak pinjam, berjudul:\n\n" . 
                
                // Book title with randomization
                "*\"{$book->title}\"*\n" . 
                
                // Due date status with more randomization
                (in_array($label, ['H+1', 'H+7']) ? 
                    Arr::random([
                        "sudah terlambat " . $daysText . ", tepatnya pada " . $formattedDueDate . ".\n\n",
                        "Telah lewat " . $daysText . ", dengan tanggal jatuh tempo pada " . $formattedDueDate . ".\n\n",
                        "Buku ini sudah melewati tenggat waktu " . $daysText . ", jatuh tempo pada " . $formattedDueDate . ".\n\n",
                        "Buku ini terlambat " . $daysText . ", seharusnya kembali pada " . $formattedDueDate . ".\n\n",
                        "Peminjaman buku ini telah lewat " . $daysText . ", jatuh tempo pada " . $formattedDueDate . ".\n\n",
                        "Buku ini sudah jatuh tempo pada " . $formattedDueDate . " dengan keterlambatan " . $daysText . ".\n\n",
                        "Buku ini terlambat " . $daysText . " , seharusnya kembali pada " . $formattedDueDate . ".\n\n",
                    ]) :
                    Arr::random([
                        "akan jatuh tempo dalam " . $daysText . ", tepatnya pada " . $formattedDueDate . ".\n\n",
                        "Harap segera kembalikan dalam waktu " . $daysText . ", yang jatuh tempo pada " . $formattedDueDate . ".\n\n",
                        "Buku ini akan segera jatuh tempo dalam waktu " . $daysText . ", yaitu pada " . $formattedDueDate . ".\n\n",
                        "Segera kembalikan buku ini dalam " . $daysText . ", dengan tanggal jatuh tempo pada " . $formattedDueDate . ".\n\n",
                        "Buku ini akan jatuh tempo dalam waktu " . $daysText . ", yaitu pada " . $formattedDueDate . ".\n\n",
                        "Buku ini harus segera dikembalikan dalam " . $daysText . ", dengan jatuh tempo pada " . $formattedDueDate . ".\n\n",
                        "Buku ini terjatuh tempo dalam " . $daysText . " hari, yang jatuh pada " . $formattedDueDate . ".\n\n",
                    ])
                ) .
                
                // Request to return the book with more variations
                Arr::random([
                    "Mohon untuk dikembalikan tepat waktu ya 😊",
                    "Kami mohon agar buku ini dapat segera dikembalikan 😊",
                    "Tolong kembalikan buku ini tepat waktu ya 😊",
                    "Mohon segera mengembalikan buku ini ya 😊",
                    "Kami harap buku ini dapat segera dikembalikan tepat waktu 😊",
                    "Kami mohon agar buku ini bisa dikembalikan tepat waktu ya 😊",
                    "Buku ini kami harap bisa segera dikembalikan dalam waktu yang telah ditentukan 😊",
                    "Silakan kembalikan buku ini tepat waktu ya, terima kasih 😊",
                ]) . "\n" .
                
                // Contact message with more variations
            
                
                // Gratitude message with more variations
                Arr::random([
                    "Terima kasih dan ditunggu kabar pengembaliannya ya🙏📚",
                    "Kami ucapkan terima kasih dan tunggu kabar pengembalian bukunya ya🙏📚",
                    "Terima kasih banyak, kami menunggu pengembalian bukunya🙏📚",
                    "Kami sangat menghargai pengembalian buku tepat waktu, terima kasih🙏📚",
                    "Terima kasih atas kerjasamanya, kami tunggu pengembalian bukunya ya🙏📚",
                    "Kami sangat berterima kasih jika buku ini dapat segera dikembalikan tepat waktu🙏📚",
                    "Terima kasih, dan kami menantikan pengembalian buku ini ya🙏📚",
                    "Terima kasih atas perhatian dan kerjasamanya, kami tunggu pengembalian bukunya🙏📚",
                ]) . "\n\n" .
                
                // Disclaimer part with more variations
                Arr::random([
                    "*Pesan ini bersifat satu arah. Layanan konfirmasi perpanjangan hanya tersedia sampai jam 21.00 setiap harinya. Chat di atas jam tersebut akan dibalas keesokan harinya.*",
                    "*Pesan ini dikirimkan otomatis. Jika ingin memperpanjang peminjaman, layanan tersedia hingga jam 21.00 setiap harinya. Chat setelah jam tersebut akan dibalas pada hari berikutnya.*",
                    "*Pesan ini bersifat informatif dan otomatis. Konfirmasi perpanjangan hanya dapat dilakukan sampai pukul 21.00 setiap harinya. Pesan yang diterima setelah jam tersebut akan dibalas pada hari berikutnya.*",
                    "*Pesan ini tidak memerlukan balasan, namun jika ingin memperpanjang, layanan tersedia hingga jam 21.00. Chat di luar jam tersebut akan dibalas pada hari berikutnya.*",
                    "*Pesan ini adalah pemberitahuan otomatis. Konfirmasi perpanjangan hanya bisa dilakukan sebelum pukul 21.00. Pesan yang diterima setelah jam tersebut akan dibalas keesokan harinya.*",
                    "*Pesan ini dikirim otomatis, konfirmasi perpanjangan hanya bisa dilakukan sampai pukul 21.00 setiap harinya. Pesan setelah jam tersebut akan dibalas besok.*",
                    "*Pesan ini bersifat pemberitahuan, konfirmasi perpanjangan hanya tersedia sampai jam 21.00 setiap harinya. Untuk chat yang masuk setelah jam tersebut, kami akan balas di hari berikutnya.*",
                    "*Pesan ini bersifat otomatis. Perpanjangan hanya bisa dilakukan sampai jam 21.00, chat di luar jam tersebut akan dibalas keesokan harinya.*",
                ]);


                $this->sendWithRetry($formattedPhone, $message, $apiKey, $apiUrl, $book);
            }
        }

        $this->info("✅ Semua pesan telah dikirim.");
    }

    protected function sendWithRetry($phone, $message, $apiKey, $apiUrl, $book = null)
    {
        $maxAttempts = 10;
        $attempt = 1;

        // Create message log entry
        $messageLog = null;
        if ($book) {
            $messageLog = MessageLog::create([
                'borrower_id' => $book->borrower_id,
                'book_id' => $book->id,
                'message' => $message,
                'status' => 'pending',
            ]);
        }

        while ($attempt <= $maxAttempts) {
            try {
                $this->info("📱 [Percobaan $attempt] Kirim ke: $phone");
                $response = $this->sendWhatsApp($apiKey, $apiUrl, $phone, $message);
                $status = $response->status();
                $body = $response->body();
                $responseData = json_decode($body, true);

                if ($status === 403 && str_contains($body, '405')) {
                    $this->warn("🔁 Gagal (403/405), retry dalam 5 detik...");
                    
                    if ($messageLog) {
                        $messageLog->update([
                            'status' => 'retry',
                            'response' => $body
                        ]);
                    }
                    
                    sleep(5);
                    $attempt++;
                    continue;
                }

                if ($status === 429 && str_contains($body, 'retry_after')) {
                    $retryAfter = $responseData['retry_after'] ?? 6;
                    $this->warn("⏱ Rate-limit. Tunggu $retryAfter detik...");
                    
                    if ($messageLog) {
                        $messageLog->update([
                            'status' => 'rate-limited',
                            'response' => $body
                        ]);
                    }
                    
                    sleep($retryAfter);
                    $attempt++;
                    continue;
                }

                if ($response->successful()) {
                    $this->info("✅ Sukses ke: $phone");
                    $this->info("Response: " . $body);
                    
                    // Update message log with successful status
                    if ($messageLog) {
                        $messageLog->update([
                            'status' => 'sent',
                            'response' => $body,
                            'sent_at' => now()
                        ]);
                    }
                    
                    sleep(300); // delay antar pesan
                    return true;
                }

                $this->error("❌ Gagal kirim ke: $phone | Status: $status");
                \Log::warning("WA Gagal: $phone - $body");
                
                if ($messageLog) {
                    $messageLog->update([
                        'status' => 'failed',
                        'response' => $body
                    ]);
                }
                
                sleep(6);
                $attempt++;

            } catch (\Exception $e) {
                $this->error("⚠ Exception untuk $phone: " . $e->getMessage());
                \Log::error("Exception WA: $phone - " . $e->getMessage());
                
                if ($messageLog) {
                    $messageLog->update([
                        'status' => 'error',
                        'response' => $e->getMessage()
                    ]);
                }
                
                sleep(6);
                $attempt++;
            }
        }

        $this->error("⛔ Gagal permanen kirim ke: $phone setelah $maxAttempts percobaan.");
        \Log::error("⛔ Gagal permanen ke $phone setelah $maxAttempts percobaan.");
        
        if ($messageLog) {
            $messageLog->update([
                'status' => 'failed_permanent',
                'response' => "Failed after $maxAttempts attempts"
            ]);
        }
        
        return false;
    }

    protected function sendWhatsApp($apiKey, $apiUrl, $phone, $message)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($apiUrl, [
            'to' => $phone,
            'text' => $message
        ]);
    }

    protected function formatPhoneNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        if (strpos($number, '0') === 0) {
            $number = substr($number, 1);
        }
        return '+62' . $number;
    }

    protected function calculateDays($dueDate)
    {
        $now = Carbon::now();
        $due = Carbon::parse($dueDate); 
        $days = $now->diffInDays($due, false);
        
        if ($days < 0) {
            return abs($days) . " hari terlewat";
        } elseif ($days == 0) {
            return "hari ini";
        } else {
            return $days . " hari";
        }
    }
}