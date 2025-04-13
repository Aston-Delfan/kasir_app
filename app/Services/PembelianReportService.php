<?php

namespace App\Services;

use App\Models\Pembelian;
use Carbon\Carbon;

class PembelianReportService extends PdfReportService
{
    /**
     * @inheritDoc
     */
    protected function getData(?int $id = null, array $filters = []): array
    {
        $query = Pembelian::with(['supplier', 'detailPembelians.produk']);

        // Filter by specific ID if provided
        if ($id) {
            $query->where('id', $id);
        }

        // Apply date filters if provided
        if (isset($filters['date_from'])) {
            $query->whereDate('tanggal_pembelian', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('tanggal_pembelian', '<=', $filters['date_to']);
        }

        // Apply supplier filter if provided
        if (isset($filters['supplier_id']) && $filters['supplier_id']) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        $pembelians = $query->get();

        // Calculate total amount for each purchase and grand total
        $grandTotal = 0;
        foreach ($pembelians as $pembelian) {
            $total = 0;
            foreach ($pembelian->detailPembelians as $detail) {
                $detail->subtotal = $detail->jumlah_produk * $detail->produk->harga;
                $total += $detail->subtotal;
            }
            $pembelian->total = $total;
            $grandTotal += $total;
        }

        return [
            'pembelians' => $pembelians,
            'grandTotal' => $grandTotal,
            'filters' => $filters,
            'reportDate' => Carbon::now()->format('d F Y'),
            'title' => $id ? 'Detail Pembelian' : 'Laporan Pembelian',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getViewName(): string
    {
        return 'reports.pembelian';
    }

    /**
     * @inheritDoc
     */
    protected function getFileName(?int $id = null): string
    {
        return $id
            ? 'pembelian_' . $id . '_' . Carbon::now()->format('Ymd')
            : 'laporan_pembelian_' . Carbon::now()->format('Ymd');
    }
}
