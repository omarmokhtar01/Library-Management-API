<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'borrow_date',
    'return_date',
    'status',
    'book_id',
    'user_id',
    ];
    public function book(){
        return $this->belongsTo(Book::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    protected $hidden=[
        'book_id',
        'user_id',
    ];
}
