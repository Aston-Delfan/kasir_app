<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeController extends Controller
{
    public function printBarcode($id)
    {
        $produk = Produk::findOrFail($id);

        return view('barcode.print', [
            'produk' => $produk
        ]);
    }

    public function printBulkBarcode(Request $request)
    {
        $ids = explode(',', $request->ids);
        $produks = Produk::whereIn('id', $ids)->get();

        return view('barcode.print-bulk', [
            'produks' => $produks
        ]);
    }
}
