<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Stok Menipis';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Produk::query()
                    ->where('stok', '<', 10)
                    ->orderBy('stok')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_produk')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.nama_kategori')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->color(fn (Produk $record): string => $record->stok == 0 ? 'danger' : 'warning'),
                Tables\Columns\TextColumn::make('harga')
                    ->money('idr')
                    ->label('Harga'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->url(fn (Produk $record): string => route('filament.admin.resources.produks.edit', ['record' => $record]))
                    ->icon('heroicon-m-pencil-square'),
            ])
            ->paginated([5, 10, 15]);
    }
}
