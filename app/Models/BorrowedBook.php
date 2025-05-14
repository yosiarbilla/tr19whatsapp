<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedBook extends Model {
    use HasFactory;

    protected $fillable = ['borrower_id', 'title', 'due_date', 'is_returned', 'returned_at'];

    protected $casts = [
        'is_returned' => 'boolean',
        'due_date' => 'date',
        'returned_at' => 'datetime',
    ];

    public function borrower() {
        return $this->belongsTo(Borrower::class);
    }
    // In BorrowedBook.php model
public function messageLogs()
{
    return $this->hasMany(MessageLog::class, 'book_id');
}

}

