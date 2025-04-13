<?php

namespace App\Http\Controllers;

use App\Services\PembelianReportService;
use App\Services\PenjualanReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $pembelianReportService;
    protected $penjualanReportService;

    public function __construct(
        PembelianReportService $pembelianReportService,
        PenjualanReportService $penjualanReportService
    ) {
        $this->pembelianReportService = $pembelianReportService;
        $this->penjualanReportService = $penjualanReportService;
    }

    /**
     * Generate PDF report for all purchases or a specific purchase
     */
    public function pembelian(Request $request, ?int $id = null)
    {
        $filters = $request->only(['date_from', 'date_to', 'supplier_id']);

        return $this->pembelianReportService->streamPdf($id, $filters);
    }

    /**
     * Generate PDF report for all sales or a specific sale
     */
    public function penjualan(Request $request, ?int $id = null)
    {
        $filters = $request->only(['date_from', 'date_to', 'pelanggan_id']);

        return $this->penjualanReportService->streamPdf($id, $filters);
    }

    /**
     * Download PDF report for all purchases or a specific purchase
     */
    public function downloadPembelian(Request $request, ?int $id = null)
    {
        $filters = $request->only(['date_from', 'date_to', 'supplier_id']);

        return $this->pembelianReportService->downloadPdf($id, $filters);
    }

    /**
     * Download PDF report for all sales or a specific sale
     */
    public function downloadPenjualan(Request $request, ?int $id = null)
    {
        $filters = $request->only(['date_from', 'date_to', 'pelanggan_id']);

        return $this->penjualanReportService->downloadPdf($id, $filters);
    }
}
