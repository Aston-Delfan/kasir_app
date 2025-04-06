<?php

namespace App\Filament\Resources\DetailPembelianResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\Summarizers\Summarizer;

class DetailPembelianWidget extends BaseWidget
{

    public $pembelianId;
    public function mount($record)
    {
        $this->pembelianId = $record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\DetailPembelian::query()->where('pembelian_id', $this->pembelianId)
            )
            ->columns([
                Tables\Columns\TextColumn::make('produk.nama_produk')->Label('Nama Produk'),
                Tables\Columns\TextColumn::make('jumlah_produk')->Label('Jumlah Produk')->alignCenter(),
                Tables\Columns\TextColumn::make('produk.harga')->Label('Harga Produk')->money('IDR')->AlignEnd(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->getStateUsing(function($record){
                        return $record->jumlah_produk * $record->produk->harga;
                    })
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
