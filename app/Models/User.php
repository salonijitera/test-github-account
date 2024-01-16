<?php

namespace App\Models;

use App\Models\Traits\FilterQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    use FilterQueryBuilder;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'password_hash',
        'email',
        'email_verified',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    public function emailVerificationTokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmailVerificationToken::class, 'user_id');
    }
}
