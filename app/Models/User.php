<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class User extends Model implements Authenticatable
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'users';
    protected $fillable = ['username', 'fullName', 'password'];
    protected $hidden = ['password'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    public function transaction() : BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
    public function getAuthIdentifierName(): string
    {
       return 'id';
    }

    public function getAuthIdentifier()
    {
       return $this->username;
    }

    public function getAuthPasswordName()
    {
        return  $this->password;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->token;
    }

    public function setRememberToken($value)
    {
       $this->token = $value;
    }

    public function getRememberTokenName()
    {
        return 'token';
    }
}
