<?php

namespace App\Providers;

use App\Contracts\CardGenerator;
use App\Contracts\TransactionContract;
use App\Services\PaymentWall\AccountNumberGenerator;
use App\Services\PaymentWall\PaymentTransaction;
use Illuminate\Support\ServiceProvider;

class ContractServiceProvider extends ServiceProvider
{
    /**
     * The application's contracts.
     *
     * @var array
     */
    protected $services = [
        CardGenerator::class => AccountNumberGenerator::class,
        TransactionContract::class => PaymentTransaction::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->services as $key => $value) {
            $this->app->bindIf($key, $value);
        }
    }
}
