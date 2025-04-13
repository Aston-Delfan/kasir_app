<?php

namespace App\Providers;

use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Observers\DetailPembelianObserver;
use App\Observers\DetailPenjualanObserver;

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
        DetailPembelian::observe(DetailPembelianObserver::class);
    }
}
