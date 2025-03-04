<?php

namespace App\Filament\Resources\DetailPembelianResource\Pages;

use App\Filament\Resources\DetailPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailPembelian extends EditRecord
{
    protected static string $resource = DetailPembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
