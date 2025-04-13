<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Print/view reports
    Route::get('/reports/pembelian/{id?}', [ReportController::class, 'pembelian'])->name('reports.pembelian');
    Route::get('/reports/penjualan/{id?}', [ReportController::class, 'penjualan'])->name('reports.penjualan');

    // Download reports
    Route::get('/reports/pembelian/{id?}/download', [ReportController::class, 'downloadPembelian'])->name('reports.pembelian.download');
    Route::get('/reports/penjualan/{id?}/download', [ReportController::class, 'downloadPenjualan'])->name('reports.penjualan.download');
});
