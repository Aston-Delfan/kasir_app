<?php

namespace App\Services;

use App\Models\Penjualan;
use Carbon\Carbon;

class PenjualanReportService extends PdfReportService
{
    /**
     * @inheritDoc
     */
    protected function getData(?int $id = null, array $filters = []): array
    {
        $query = Penjualan::with(['pelanggan', 'detailPenjualans.produk']);

        // Filter by specific ID if provided
        if ($id) {
            $query->where('id', $id);
        }

        // Filter by array of IDs if provided
        if (isset($filters['ids']) && !empty($filters['ids'])) {
            $query->whereIn('id', $filters['ids']);
        }

        // Apply date filters if provided
        if (isset($filters['date_from'])) {
            $query->whereDate('tanggal_penjualan', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('tanggal_penjualan', '<=', $filters['date_to']);
        }

        // Apply customer filter if provided
        if (isset($filters['pelanggan_id']) && $filters['pelanggan_id']) {
            $query->where('pelanggan_id', $filters['pelanggan_id']);
        }

        $penjualans = $query->get();

        // Calculate total amount for each sale and grand total
        $grandTotal = 0;
        foreach ($penjualans as $penjualan) {
            $total = 0;
            foreach ($penjualan->detailPenjualans as $detail) {
                $detail->subtotal = $detail->jumlah_produk * $detail->harga;
                $total += $detail->subtotal;
            }
            $penjualan->total = $total;
            $grandTotal += $total;
        }

        return [
            'penjualans' => $penjualans,
            'grandTotal' => $grandTotal,
            'filters' => $filters,
            'reportDate' => Carbon::now()->format('d F Y'),
            'title' => $id ? 'Detail Penjualan' : 'Laporan Penjualan',
            'count' => $penjualans->count(),
        ];
    }

    /**
     * Generate a PDF report for multiple sales
     *
     * @param array $ids Array of sale IDs
     * @return \Illuminate\Http\Response
     */
    public function generateBulkPdf(array $ids)
    {
        return $this->streamPdf(null, ['ids' => $ids]);
    }

    /**
     * @inheritDoc
     */
    protected function getViewName(): string
    {
        return 'reports.penjualan';
    }

    /**
     * @inheritDoc
     */
    protected function getFileName(?int $id = null): string
    {
        return $id
            ? 'penjualan_' . $id . '_' . Carbon::now()->format('Ymd')
            : 'laporan_penjualan_' . Carbon::now()->format('Ymd');
    }
}
