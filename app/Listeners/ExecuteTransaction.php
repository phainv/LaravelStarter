<?php

namespace App\Listeners;

use Exception;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\Transaction;
use App\Events\TransactionCreated;

class ExecuteTransaction
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TransactionCreated $event)
    {
        $transaction = $event->transaction;

        if ($transaction->status == 'processing') {
            $account = $transaction->account;

            switch ($transaction->type) {
                case 'topup':
                    $this->executeTopup($account, $transaction);
                    break;

                case 'withdraw':
                    $this->executeWithdraw($account, $transaction);
                    break;

                default:
                    return 'default';
                    break;
            }
        }

        return 'end';
    }

    /**
     * Execute topup.
     *
     * @param  \App\Models\Account     $account
     * @param  \App\Models\Transaction $transaction
     * @return Illuminate\Http\Response
     */
    protected function executeTopup(Account $account, Transaction $transaction)
    {
        try {
            if ($transaction->cash > $account->topup_limit) {
                throw new Exception('ERROR-001');
            }

            $totallyTransaction = $account->transactions()
                                        ->successful()->type($transaction->type)->whereDate('created_at', '=', Carbon::today()
                                        ->toDateString())->get()->sum('cash');

            if (($totallyTransaction + $transaction->cash) > $account->topup_limit) {
                throw new Exception('ERROR-001');
            }

            if (! $account->status) {
                throw new Exception('ERROR-003');
            }

            $account->amount = $account->amount + $transaction->cash;
            $account->save();

            $transaction->status = Transaction::SUCCESSFUL;
            $transaction->save();

            return;
        } catch (Exception $e) {
            $transaction->reason = $e->getMessage();
            $transaction->status = Transaction::UNSUCCESSFUL;
            $transaction->save();
        }
    }

    /**
     * Execute topup.
     *
     * @param  \App\Models\Account     $account
     * @param  \App\Models\Transaction $transaction
     * @return Illuminate\Http\Response
     */
    protected function executeWithdraw(Account $account, Transaction $transaction)
    {
        try {
            if ($transaction->cash > $account->withdraw_limit) {
                throw new Exception('ERROR-002');
            }

            $totallyTransaction = $account->transactions()
                                        ->successful()->type($transaction->type)->whereDate('created_at', '=', Carbon::today()
                                        ->toDateString())->get()->sum('cash');

            if (($totallyTransaction + $transaction->cash) > $account->withdraw_limit) {
                throw new Exception('ERROR-002');
            }

            if (! $account->status) {
                throw new Exception('ERROR-003');
            }

            if ($account->amount < $transaction->cash) {
                throw new Exception('ERROR-004');
            }

            $account->amount = $account->amount - $transaction->cash;
            $account->save();

            $transaction->status = Transaction::SUCCESSFUL;
            $transaction->save();

            return;
        } catch (Exception $e) {
            $transaction->reason = $e->getMessage();
            $transaction->status = Transaction::UNSUCCESSFUL;
            $transaction->save();
        }
    }
}
