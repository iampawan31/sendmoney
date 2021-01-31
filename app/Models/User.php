<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'image',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['balance'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['transactions'];

    public function getBalanceAttribute()
    {
        return $this->balance();
    }

    /**
     * isUserVerified
     *
     * @return void
     */
    public function isUserVerified()
    {
        return $this->phone_verified_at !== null && $this->email_verified_at !== null;
    }

    /**
     * transactions
     *
     * @return void
     */
    public function transactions()
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * validTransactions
     *
     * @return void
     */
    public function validTransactions()
    {
        return $this->transactions()->where('status', 1);
    }

    /**
     * credit
     *
     * @return void
     */
    public function credit()
    {
        return $this->validTransactions()
            ->where('type', 'credit')
            ->sum('amount');
    }

    /**
     * debit
     *
     * @return void
     */
    public function debit()
    {
        return $this->validTransactions()
            ->where('type', 'debit')
            ->sum('amount');
    }

    /**
     * balance
     *
     * @return void
     */
    public function balance()
    {
        return $this->credit() - $this->debit();
    }

    /**
     * allowWithdraw
     *
     * @param  mixed $amount
     * @return bool
     */
    public function allowWithdraw($amount): bool
    {
        return $this->balance() >= $amount;
    }
}
