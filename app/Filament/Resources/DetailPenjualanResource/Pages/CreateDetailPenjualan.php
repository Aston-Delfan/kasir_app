<?php

namespace App\Filament\Resources\DetailPenjualanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DetailPenjualanResource;
use Filament\Actions\Action;
use App\Filament\Resources\DetailPenjualanResource\Widgets\DetailPenjualanWidget;

class CreateDetailPenjualan extends CreateRecord
{
    protected static string $resource = DetailPenjualanResource::class;
    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('Simpan')
                ->submit('create')
                ->keyBindings(['mod+s']),
        ];
    }

    protected function getRedirectUrl():string
    {
        $id = $this->record->penjualan_id;
        return route(
            'filament.admin.resources.detail-penjualans.create',['penjualan_id' => $id]
        );
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return 1;
    }

    public function getFooterWidgets(): array
    {
        return [
            DetailPenjualanWidget::make([
                'record' => request('pembelian_id'),
         ]),
        ];
    }
}
