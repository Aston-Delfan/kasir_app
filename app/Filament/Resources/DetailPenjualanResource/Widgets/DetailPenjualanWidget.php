<?php

namespace App\Filament\Resources\DetailPenjualanResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\Summarizers\Summarizer;

class DetailPenjualanWidget extends BaseWidget
{
    public $penjualanId;

    public function mount($record)
    {
        $this->penjualanId = $record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\DetailPenjualan::query()->where('penjualan_id', $this->penjualanId)
            )
            ->columns([
                Tables\Columns\TextColumn::make('produk.nama_produk')->Label('Nama Produk'),
                Tables\Columns\TextColumn::make('jumlah_produk')->Label('Jumlah Produk')->alignCenter(),
                Tables\Columns\TextColumn::make('harga')->Label('Harga Produk')->money('IDR')->AlignEnd(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->Label('Subtotal')
                    ->money('IDR')
                    ->alignEnd()
                    ->summarize(
                        Summarizer::make()
                            ->using(function ($query){
                                return $query->sum(DB::raw('subtotal'));
                            })
                            ->money('IDR')
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        TextInput::make('jumlah_produk')->required(),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
