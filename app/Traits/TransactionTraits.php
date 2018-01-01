<?php

namespace App\Traits;

use App\Models\Account;
use App\Contracts\TransactionContract;

trait TransactionTraits
{
    /**
     * Make topup account.
     *
     * @param  \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function topup(Account $account)
    {
        $service = app(TransactionContract::class);
        $service->newTransaction($account, request('cash'), request('type'));
    }
}
