<?php

namespace App\Services\PaymentWall;

use App\Models\Account;
use App\Contracts\CardGenerator;

class AccountNumberGenerator implements CardGenerator
{
    /**
     * Generate an unique card number.
     *
     * @return int
     */
    public function generate()
    {
        do {
            $card = $this->randomNumber();
        } while (! $this->isValid($card));

        return $card;
    }

    /**
     * Generate card number number.
     *
     * @param  int $length
     * @return int
     */
    public function randomNumber()
    {
        $last = Account::latest('id')->first();

        if ($last) {
            return $last->account_number + 1;
        }

        return 888866668888;
    }

    /**
     * Check if the card number is valid.
     *
     * @param  int $code
     * @return bool
     */
    public function isValid($card)
    {
        if (Account::where('account_number', '=', $card)->first()) {
            return false;
        }

        return true;
    }
}
