<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    const TOPUP = 'topup';

    const WITHDRAW = 'withdraw';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_number', 'amount', 'topup_limit', 'withdraw_limit', 'currency', 'default', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_virtual'
    ];

    /**
     * Get the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check account is virtual account.
     * 
     * @return boolean
     */
    public function isVirtual()
    {
        return $this->currency ? false : true;
    }

    /**
     * Get fullname attribute.
     *
     * @return array|null
     */
    public function getIsVirtualAttribute()
    {
        return $this->isVirtual();
    }

    /**
     * Get all transaction's of account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
