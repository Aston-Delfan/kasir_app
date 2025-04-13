@if($getRecord()->barcode)
    <div class="flex flex-col items-center">
        <div class="barcode-wrapper">
            {!! $getRecord()->getBarcodeHtml() !!}
        </div>
        <div class="text-xs text-center mt-1">{{ $getRecord()->barcode }}</div>
    </div>
@else
    <span class="text-gray-400">Tidak ada barcode</span>
@endif
