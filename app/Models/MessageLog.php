<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageLog extends Model
{
    protected $fillable = [
        'borrower_id',
        'book_id',
        'message',
        'status',
        'response',
        'sent_at'
    ];
    
    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }
    
    public function book()
    {
        return $this->belongsTo(BorrowedBook::class, 'book_id');
    }
}
