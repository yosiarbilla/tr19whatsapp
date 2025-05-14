<?php

namespace App\Imports;

use App\Models\Borrower;
use App\Models\BorrowedBook;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class BorrowersImport implements ToCollection
{
    public function collection(Collection $rows): void
    {
        // Log data import untuk debugging
        \Log::info("Memulai import data buku");
        
        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Skip header CSV

            // Debug informasi
            \Log::info("Memproses baris ".($index+1).": ".json_encode($row));

            // Pastikan ada judul buku dan email
            if (empty($row[0]) || empty($row[13])) {  
                \Log::error("Data kosong pada baris {$index}: " . json_encode($row));
                continue;
            }
            
            $title = $row[0]; // title di kolom A
            $firstName = isset($row[10]) ? $row[10] : ''; // first_name di kolom K
            $lastName = isset($row[11]) ? $row[11] : ''; // last_name di kolom L
            $email = isset($row[13]) ? $row[13] : ''; // email di kolom N
            $phone = isset($row[15]) ? $row[15] : ''; // phone di kolom P
            
            // Tentukan default due_date (2 minggu dari sekarang jika tidak ada)
            $dueDate = Carbon::now()->addWeeks(2)->format('Y-m-d');
            
            // Jika ada kolom due_date di Excel (umumnya kolom Q/R/S terakhir)
            // Coba beberapa indeks yang mungkin untuk due_date
            foreach ([18, 19, 20, 17, 16] as $possibleIndex) {
                if (isset($row[$possibleIndex]) && !empty($row[$possibleIndex])) {
                    $tempDueDate = $row[$possibleIndex];
                    
                    if (is_numeric($tempDueDate)) {
                        try {
                            // Jika due_date dalam format Excel timestamp, konversi ke tanggal
                            $dueDate = Date::excelToDateTimeObject($tempDueDate)->format('Y-m-d');
                            break;
                        } catch (\Exception $e) {
                            \Log::error("Error konversi Excel date: " . $e->getMessage());
                        }
                    } elseif (strtotime($tempDueDate)) {
                        // Jika sudah dalam format string tanggal, gunakan langsung
                        $dueDate = Carbon::parse($tempDueDate)->format('Y-m-d');
                        break;
                    }
                }
            }
            
            // Log info peminjam dan buku
            \Log::info("Peminjam: $firstName $lastName ($email), Buku: $title, Due date: $dueDate");

            // Buat atau temukan Borrower berdasarkan email
            $borrower = Borrower::firstOrCreate(
                ['email' => $email],
                [
                    'name' => trim("$firstName $lastName"),
                    'phone' => $phone
                ]
            );

            // Periksa apakah peminjam sudah memiliki buku dengan judul yang sama dan belum dikembalikan
            $existingBook = BorrowedBook::where('borrower_id', $borrower->id)
                ->where('title', $title)
                ->where('is_returned', false)
                ->first();
            
            if ($existingBook) {
                // Jika ada, update saja tanggal jatuh temponya
                $existingBook->due_date = $dueDate;
                $existingBook->save();
                \Log::info("Update peminjaman yang sudah ada: ID {$existingBook->id}");
            } else {
                // Jika tidak ada atau sudah dikembalikan, buat peminjaman baru
                $newBook = BorrowedBook::create([
                    'borrower_id' => $borrower->id,
                    'title' => $title,
                    'due_date' => $dueDate,
                    'is_returned' => false
                ]);
                \Log::info("Buat peminjaman baru: ID {$newBook->id}");
            }
        }
        
        \Log::info("Import data selesai");
    }
}

