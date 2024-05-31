<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $primaryKey = 'id';

    protected $fillable= ['title', 'code', 'stock', 'author', 'publisher', 'status'];
    public $incrementing = true;
    public $timestamps = true;

    public function transaction() : BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'book_transaction_id');
    }

}
