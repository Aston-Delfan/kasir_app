<?php

namespace App\Providers;

use App\Models\DetailPenjualan;
use App\Observers\DetailPenjualanObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        DetailPenjualan::observe(DetailPenjualanObserver::class);
    }
}
