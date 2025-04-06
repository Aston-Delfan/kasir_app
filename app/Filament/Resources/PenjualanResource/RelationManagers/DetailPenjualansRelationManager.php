<?php

namespace App\Filament\Resources\PenjualanResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Produk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Resources\RelationManagers\RelationManager;

class DetailPenjualansRelationManager extends RelationManager
{
    protected static string $relationship = 'detailPenjualans';

    protected static ?string $recordTitleAttribute = 'produk.nama_produk';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('produk_id')
                    ->label('Pilih Produk')
                    ->options(function (callable $get) {
                        $penjualan = $this->getOwnerRecord();
                        $supplierId = $penjualan->supplier_id;
                        return Produk::query()
                            ->when($supplierId, function ($query) use ($supplierId) {
                                $query->whereHas('suppliers', function ($q) use ($supplierId) {
                                    $q->where('suppliers.id', $supplierId);
                                });
                            })
                            ->pluck('nama_produk', 'id')
                            ->toArray();
                    })
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $produk = Produk::find($state);
                        $jumlah = $get('jumlah_produk') ?: 1;
                        $subtotal = $jumlah * $produk->harga;
                        $set('subtotal', $subtotal);
                        $set('harga', $produk->harga);
                    }),
                Forms\Components\TextInput::make('harga')
                    ->label('Harga')
                    ->disabled()
                    ->prefix('Rp ')
                    ->numeric(),
                Forms\Components\TextInput::make('jumlah_produk')
                    ->label('Jumlah Produk')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->reactive()
                    ->debounce(600)
                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                        $jumlah = $state;
                        $harga = $get('harga');
                        $subtotal = $jumlah * $harga;
                        $set('subtotal', $subtotal);
                    }),
                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->disabled()
                    ->reactive()
                    ->dehydrated()
                    ->prefix('Rp '),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
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
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR')
                    ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
