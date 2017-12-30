<?php

namespace App\Contracts;

use App\Models\Account;

interface TransactionContract
{
    /**
     * Topup account.
     *
     * @param  \App\Models\Account $account
     * @param  int  $cash
     * @return void
     */
    public function newTransaction(Account $account, $cash, $type);
}
