<?php

use Illuminate\Support\Facades\Route;
use App\Services\PembelianReportService;
use App\Services\PenjualanReportService;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});


//single record reports
Route::get('reports/pembelian/{id}', function ($id, PembelianReportService $service) {
    return $service->streamPdf($id);
})->name('reports.pembelian');

Route::get('reports/penjualan/{id}', function ($id, PenjualanReportService $service) {
    return $service->streamPdf($id);
})->name('reports.penjualan');


//routes for bulk printing
// Route::get('reports/pembelian-bulk', function (PembelianReportService $service) {
//     $idsString = request('ids');
//     if (empty($idsString)) {
//         return back()->with('error', 'Tidak ada data yang dipilih.');
//     }

//     $ids = array_filter(explode(',', $idsString), 'is_numeric');

//     if (empty($ids)) {
//         return back()->with('error', 'ID yang dipilih tidak valid.');
//     }

//     return $service->generateBulkPdf($ids);
// })->name('reports.pembelian.bulk');

// Route::get('reports/penjualan-bulk', function (PenjualanReportService $service) {
//     $idsString = request('ids');
//     if (empty($idsString)) {
//         return back()->with('error', 'Tidak ada data yang dipilih.');
//     }

//     $ids = array_filter(explode(',', $idsString), 'is_numeric');

//     if (empty($ids)) {
//         return back()->with('error', 'ID yang dipilih tidak valid.');
//     }

//     return $service->generateBulkPdf($ids);
// })->name('reports.penjualan.bulk');


//routes for printing all
Route::get('reports/pembelian-all', function (PembelianReportService $service) {
    return $service->streamPdf();
})->name('reports.pembelian.all');

Route::get('reports/penjualan-all', function (PenjualanReportService $service) {
    return $service->streamPdf();
})->name('reports.penjualan.all');
