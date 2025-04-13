<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Penjualan;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use App\Models\DetailPenjualan;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DetailPenjualanResource\Pages;
use DesignTheBox\BarcodeField\Forms\Components\BarcodeInput;
use App\Filament\Resources\DetailPenjualanResource\RelationManagers;

class DetailPenjualanResource extends Resource
{
    protected static ?string $model = DetailPenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        $penjualan = new Penjualan();

        $penjualanId = request('penjualan_id');

        if(request()->filled('penjualan_id'))
            $penjualan = Penjualan::findOrFail($penjualanId);

        return $form
        ->schema([
                Grid::make()
                    ->schema([
                        TextInput::make('tanggal_penjualan')
                        ->label('Tanggal Penjualan')
                        ->disabled()
                        ->default($penjualan->tanggal_penjualan),
                    TextInput::make('pelanggan_nama')
                        ->label('Pelanggan')
                        ->disabled()
                        ->default($penjualan->pelanggan?->nama_pelanggan),
                    TextInput::make('nomor_telepon')
                        ->label('Nomor Telepon')
                        ->disabled()
                        ->default($penjualan->pelanggan?->nomor_telepon),
                    ])->columns(3),
                    BarcodeInput::make('barcode')
                        ->label('Kode Barcode')
                        ->nullable()
                        ->placeholder('Scan atau masukkan kode barcode disini')
                        ->helperText('Tekan Enter atau Tab setelah scan barcode')
                        ->reactive()
                        ->dehydrated(false)
                        ->afterStateUpdated(function (string $state, Set $set, Get $get) {
                            // Cari produk berdasarkan barcode
                            $produk = Produk::where('barcode', $state)->first();
                            if ($produk) {
                                $jumlah = 1;
                                $subtotal = $jumlah * $produk->harga;

                                $set('produk_id', $produk->id);
                                $set('harga', $produk->harga);
                                $set('subtotal', $subtotal);
                                $set('jumlah_produk', 1);
                                // Reset barcode field setelah produk ditemukan
                                $set('barcode_scanner', '');
                            }
                        }),
                Grid::make()
                    ->schema([
                        Select::make('produk_id')
                            ->label('Pilih Produk')
                            ->options(function () {
                                return Produk::where('stok', '>', 0)
                                    ->pluck('nama_produk', 'id')
                                    ->toArray();
                            })
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $produk = Produk::find($state);
                                $jumlah = $get('jumlah_produk') ?: 1;
                                $subtotal = $jumlah * $produk->harga;
                                $set('harga', $produk->harga);
                                $set('subtotal', $subtotal);
                                $set('jumlah_produk', 1);
                            }),
                        TextInput::make('harga')
                            ->label('Harga')
                            ->disabled()
                            ->prefix('Rp ')
                            ->dehydrated()
                        ,
                        TextInput::make('jumlah_produk')
                            ->label('Jumlah Produk')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->reactive()
                            ->debounce(600)
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $jumlah = $state;
                                $harga = $get('harga') ?: 0;
                                $subtotal = $jumlah * $harga;
                                $set('subtotal', $subtotal);
                            }),
                        // Subtotal, dihitung otomatis dan tidak bisa diubah langsung
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->disabled()
                            ->reactive()
                            ->dehydrated()
                            ->default(0)
                            ->prefix('Rp ')
                        ,
                ])->columns(4),
                Hidden::make('penjualan_id')
                    ->default($penjualan->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDetailPenjualans::route('/'),
            'create' => Pages\CreateDetailPenjualan::route('/create'),
            'edit' => Pages\EditDetailPenjualan::route('/{record}/edit'),
        ];
    }
}
