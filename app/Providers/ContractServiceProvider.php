<?php

namespace App\Providers;

use App\Contracts\CardGenerator;
use Illuminate\Support\ServiceProvider;
use App\Services\PaymentWall\AccountNumberGenerator;

class ContractServiceProvider extends ServiceProvider
{
    /**
     * The application's contracts.
     *
     * @var array
     */
    protected $services = [
        CardGenerator::class => AccountNumberGenerator::class,
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
