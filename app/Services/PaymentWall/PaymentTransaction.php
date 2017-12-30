<?php

namespace App\Services\PaymentWall;

use App\Contracts\TransactionContract;
use App\Events\TransactionCreated;
use App\Models\Account;
use App\Models\Transaction;

class PaymentTransaction implements TransactionContract
{
    public function newTransaction(Account $account, $cash, $type)
    {
        $transaction = $account->transactions()->save(new Transaction([
            'cash' => $cash,
            'type' => $type,
            'status' => 'processing'
        ]));

        event(new TransactionCreated($transaction));
    }
}
