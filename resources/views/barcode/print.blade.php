<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode {{ $produk->nama_produk }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .barcode-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .barcode-wrapper {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
        .produk-info {
            text-align: center;
            margin-top: 5px;
        }
        .produk-info h3 {
            margin: 5px 0;
            font-size: 16px;
        }
        .produk-info p {
            margin: 2px 0;
            font-size: 14px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Cetak Barcode</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="barcode-container">
        <div class="barcode-wrapper">
            {!! $produk->getBarcodeHtml() !!}
        </div>
        <div class="produk-info">
            <h3>{{ $produk->nama_produk }}</h3>
            <p>Kategori: {{ $produk->category->nama_kategori }}</p>
            <p>Harga: Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
            <p>{{ $produk->barcode }}</p>
        </div>
    </div>
</body>
</html>
