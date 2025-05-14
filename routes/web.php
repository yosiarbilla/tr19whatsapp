<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorrowerController;
use App\Http\Middleware\AdminMiddleware;

// Root redirect to data-peminjam
Route::redirect('/', '/data-peminjam');

// Register AdminMiddleware
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    // Original welcome route kept for backward compatibility
    Route::get('/welcome', [BorrowerController::class, 'index'])->name('welcome');
    Route::get('/data-peminjam', [BorrowerController::class, 'index'])->name('data.peminjam');
    Route::post('/import', [BorrowerController::class, 'import'])->name('import');
    Route::post('/return-book/{id}', [BorrowerController::class, 'returnBook'])->name('return.book');
    Route::delete('/delete-book/{id}', [BorrowerController::class, 'deleteBook'])->name('delete.book');
    Route::delete('/delete-borrower/{id}', [BorrowerController::class, 'deleteBorrower'])->name('delete.borrower');
    Route::post('/bulk-delete-books', [BorrowerController::class, 'bulkDeleteBooks'])->name('bulk.delete.books');
    Route::get('/history', [BorrowerController::class, 'history'])->name('history');
    Route::post('/borrower/store', [BorrowerController::class, 'store'])->name('borrower.store');
    Route::get('/borrower/{id}/details', [BorrowerController::class, 'details'])->name('borrower.details');
});


//Route::middleware('auth')->group(function () {
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

require __DIR__.'/auth.php';
