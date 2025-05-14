<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrower;
use App\Models\BorrowedBook;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BorrowersImport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\MessageLog;

class BorrowerController extends Controller {
        public function index(Request $request) {
    $sortColumn = $request->get('sort', 'name');
    $sortDirection = $request->get('direction', 'asc');
    
    // Validate column names to prevent SQL injection
    $allowedColumns = ['name', 'email', 'phone', 'created_at', 'updated_at'];
    if (!in_array($sortColumn, $allowedColumns)) {
        $sortColumn = 'name';
    }
    
    // Ambil data peminjam dengan bukunya yang BELUM dikembalikan, pakai pagination
    $borrowers = Borrower::whereHas('borrowedBooks', function($query) {
        $query->where('is_returned', false);
    })->with(['borrowedBooks' => function($query) {
        $query->where('is_returned', false);
    }])->orderBy($sortColumn, $sortDirection)->paginate(10);

    // Get all message logs (no $id needed)
    $messageLogs = MessageLog::orderBy('created_at', 'desc')->get();

    return view('welcome', compact('borrowers', 'sortColumn', 'sortDirection', 'messageLogs'));
}


    public function import(Request $request) {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        Excel::import(new BorrowersImport, $request->file('file'));

        return redirect()->route('data.peminjam')->with('success', 'Data berhasil diimport!');
    }
    
    public function returnBook(Request $request, $id) {
        $book = BorrowedBook::with('borrower')->findOrFail($id);
        $bookTitle = $book->title;
        $borrowerName = $book->borrower->name;
        
        $book->is_returned = true;
        $book->returned_at = Carbon::now('Asia/Jakarta');
        $book->save();
        
        return redirect()->back()->with('success', "Buku \"$bookTitle\" yang dipinjam oleh $borrowerName berhasil ditandai sebagai dikembalikan!");
    }
    
    public function deleteBook(Request $request, $id) {
        try {
            $book = BorrowedBook::with('borrower')->findOrFail($id);
            $bookTitle = $book->title;
            $borrowerName = $book->borrower->name;
            
            // Log before deletion
            \Log::info("Mencoba menghapus buku: ID=$id, Judul=$bookTitle, Peminjam=$borrowerName");
            
            // Hapus log pesan terkait jika ada
            $logCount = MessageLog::where('book_id', $id)->delete();
            \Log::info("MessageLog terhapus: $logCount");
            
            // Delete the record
            $result = $book->delete();
            
            // Log deletion result
            \Log::info("Hasil penghapusan: " . ($result ? "Berhasil" : "Gagal"));
            
            if (!$result) {
                return redirect()->back()->with('error', "Gagal menghapus catatan peminjaman buku \"$bookTitle\"");
            }
            
            // Force reload data
            return redirect()->route('history')->with('success', "Catatan peminjaman buku \"$bookTitle\" oleh $borrowerName berhasil dihapus!");
        } catch (\Exception $e) {
            \Log::error("Error saat menghapus buku: " . $e->getMessage());
            return redirect()->back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }
    
    public function history(Request $request) {
        $sortColumn = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        
        // Validate column names to prevent SQL injection
        $allowedColumns = ['name', 'email', 'phone', 'created_at', 'updated_at'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'name';
        }
        
        // Clear query cache
        DB::statement("SET SESSION query_cache_type = 0");
        
        // Ambil data peminjam dengan bukunya yang SUDAH dikembalikan, pakai pagination
        $borrowers = Borrower::whereHas('borrowedBooks', function($query) {
            $query->where('is_returned', true);
        })->with(['borrowedBooks' => function($query) {
            $query->where('is_returned', true)
                  ->orderBy('returned_at', 'desc');
        }])->orderBy($sortColumn, $sortDirection)->paginate(10);
        
        return view('history', compact('borrowers', 'sortColumn', 'sortDirection'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'book_title' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah peminjam sudah ada berdasarkan email
            $borrower = Borrower::where('email', $request->email)->first();
            
            // Jika belum ada, buat peminjam baru
            if (!$borrower) {
                $borrower = new Borrower();
                $borrower->name = $request->name;
                $borrower->email = $request->email;
                $borrower->phone = $request->phone;
                $borrower->save();
            }
            
            // Buat buku yang dipinjam
            $borrowedBook = new BorrowedBook();
            $borrowedBook->borrower_id = $borrower->id;
            $borrowedBook->title = $request->book_title;
            $borrowedBook->due_date = $request->due_date;
            $borrowedBook->is_returned = false;
            $borrowedBook->save();
            
            DB::commit();
            return redirect()->route('data.peminjam')->with('success', 'Data peminjam baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkDeleteBooks(Request $request) {
        try {
            $bookIds = $request->input('book_ids', []);
            
            if (empty($bookIds)) {
                return redirect()->back()->with('error', 'Tidak ada item yang dipilih untuk dihapus');
            }
            
            // Count books before deletion for the message
            $bookCount = count($bookIds);
            
            // Log deletion attempt
            \Log::info("Mencoba menghapus $bookCount buku secara massal: " . implode(', ', $bookIds));
            
            // Hapus message logs terkait
            $logCount = MessageLog::whereIn('book_id', $bookIds)->delete();
            \Log::info("MessageLog terhapus: $logCount");
            
            // Delete the selected books
            $deletedCount = BorrowedBook::whereIn('id', $bookIds)->delete();
            \Log::info("Buku terhapus: $deletedCount");
            
            // Determine route based on referer
            $route = strpos(url()->previous(), 'history') !== false ? 'history' : 'welcome';
            
            return redirect()->route($route)->with('success', "Berhasil menghapus $deletedCount catatan peminjaman buku!");
        } catch (\Exception $e) {
            \Log::error("Error saat menghapus buku massal: " . $e->getMessage());
            return redirect()->back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function details($id)
    {
        // Find the borrower
        $borrower = Borrower::with(['borrowedBooks' => function($query) {
            // Get all books, both returned and not returned
            $query->orderBy('is_returned', 'asc')
                  ->orderBy('due_date', 'desc');
        }])->findOrFail($id);
        
        // Get message logs
        $messageLogs = MessageLog::where('borrower_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('borrower.details', compact('borrower', 'messageLogs'));
    }
    
    /**
     * Hapus peminjam beserta semua catatan bukunya
     */
    public function deleteBorrower(Request $request, $id)
    {
        try {
            // Mulai transaksi database
            DB::beginTransaction();
            
            // Cari borrower
            $borrower = Borrower::findOrFail($id);
            $borrowerName = $borrower->name;
            
            // Log deletion attempt
            \Log::info("Mencoba menghapus peminjam: ID=$id, Nama=$borrowerName");
            
            // Hapus message logs terkait
            $messageLogCount = MessageLog::where('borrower_id', $id)->delete();
            \Log::info("MessageLog terhapus: $messageLogCount");
            
            // Hapus semua buku terkait
            $bookCount = BorrowedBook::where('borrower_id', $id)->delete();
            \Log::info("Buku terhapus: $bookCount");
            
            // Hapus borrower
            $borrower->delete();
            \Log::info("Borrower berhasil dihapus");
            
            // Commit transaksi
            DB::commit();
            
            // Tentukan halaman redirect
            $redirectRoute = strpos(url()->previous(), 'history') !== false ? 'history' : 'welcome';
            return redirect()->route($redirectRoute)->with('success', "Peminjam \"$borrowerName\" beserta semua catatan peminjamannya berhasil dihapus!");
        }
        catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollback();
            \Log::error("Error saat menghapus peminjam: " . $e->getMessage());
            return redirect()->back()->with('error', "Terjadi kesalahan saat menghapus peminjam: " . $e->getMessage());
        }
    }
}


