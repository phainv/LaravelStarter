<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const UNSUCCESSFUL = 'unsuccessful';

    const SUCCESSFUL = 'successful';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'note', 'cash', 'type', 'status', 'reason',
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
        'reason_message'
    ];

    /**
     * Filter by transaction success.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', '=', static::SUCCESSFUL);
    }

    /**
     * Filter by transaction of topup.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', '=', $type);
    }

    /**
     * Get fullname attribute.
     *
     * @return array|null
     */
    public function getReasonMessageAttribute()
    {
        if ($this->reason) {
            return trans('message.'.$this->reason);
        }
    }

    /**
     * Get the account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
