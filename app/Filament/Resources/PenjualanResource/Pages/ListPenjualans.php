<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenjualans extends ListRecords
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('printAll')
                ->label('Cetak Semua')
                ->icon('heroicon-o-printer')
                ->url(route('reports.penjualan.all'))
                ->openUrlInNewTab(),
        ];
    }
}
