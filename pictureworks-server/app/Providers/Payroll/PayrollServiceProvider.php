<?php

namespace App\Providers\Payroll;

use App\Models\Payroll\PayrollItems;
use App\Payroll\PayrollItemRepository;
use Illuminate\Support\ServiceProvider;

class PayrollServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Let's try if we can skip on doing an implementation by mapping the interface
        // to an eloquent model directly :D
        // Yep, i don't think so
        $this->app->bind(PayrollItemRepository::class, function (PayrollItems $model) {
            return $model;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
