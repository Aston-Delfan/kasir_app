<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary {
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Laporan: {{ $reportDate }}</p>
    </div>

    <div class="info">
        @if(isset($filters['date_from']) || isset($filters['date_to']))
            <p>
                Periode:
                {{ isset($filters['date_from']) ? $filters['date_from'] : 'Semua' }}
                sampai
                {{ isset($filters['date_to']) ? $filters['date_to'] : 'Semua' }}
            </p>
        @endif
    </div>

    @if(count($penjualans) > 0)
        @foreach($penjualans as $penjualan)
            <div class="transaction">
                <h3>No. Transaksi: {{ $penjualan->id }}</h3>
                <p>Tanggal: {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d F Y') }}</p>
                <p>Pelanggan: {{ $penjualan->pelanggan ? $penjualan->pelanggan->nama_pelanggan : 'Umum' }}</p>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->detailPenjualans as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->produk->nama_produk }}</td>
                            <td class="text-center">{{ $detail->jumlah_produk }}</td>
                            <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total:</strong></td>
                            <td class="text-right"><strong>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endforeach

        <div class="summary">
            <h3>Total Keseluruhan: Rp {{ number_format($grandTotal, 0, ',', '.') }}</h3>
        </div>
    @else
        <p>Tidak ada data penjualan yang ditemukan.</p>
    @endif

    <div class="footer">
        <p>Laporan ini dicetak pada {{ $reportDate }}</p>
    </div>
</body>
</html>
