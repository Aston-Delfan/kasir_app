<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembelians extends ListRecords
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('printAll')
                ->label('Cetak Semua')
                ->icon('heroicon-o-printer')
                ->url(route('reports.pembelian.all'))
                ->openUrlInNewTab(),
        ];
    }
}
