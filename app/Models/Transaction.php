<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable= ['user_id', 'book_id', 'returned_date'];

    public function book() : HasMany
    {
        return $this->hasMany(Book::class, 'book_transaction_id');
    }

    public function user() : HasMany
    {
        return $this->hasMany(User::class);
    }
}
