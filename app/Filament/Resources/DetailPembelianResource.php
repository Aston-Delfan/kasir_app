<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use App\Models\DetailPembelian;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DetailPembelianResource\Pages;
use App\Filament\Resources\DetailPembelianResource\RelationManagers;

class DetailPembelianResource extends Resource
{
    protected static ?string $model = DetailPembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Header Pembelian (diasumsikan sudah diisi lewat route atau data form)
                TextInput::make('tanggal_pembelian')
                    ->label('Tanggal Pembelian')
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('supplier_nama')
                    ->label('Supplier')
                    ->disabled(),
                TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->disabled(),

                // Repeater untuk detail pembelian
                Repeater::make('detail')
                    ->label('Produk yang Dibeli')
                    ->columnSpan(2)
                    ->schema([
                        // Pilih Produk (hanya produk dari supplier yang sudah dipilih)
                        Select::make('produk_id')
                            ->label('Pilih Produk')
                            ->options(function (callable $get) {
                                $supplierId = $get('supplier_id');
                                return Produk::query()
                                    ->when($supplierId, function ($query) use ($supplierId) {
                                        $query->whereHas('suppliers', function ($q) use ($supplierId) {
                                            $q->where('suppliers.id', $supplierId);
                                        });
                                    })
                                    ->pluck('nama_produk', 'id')
                                    ->toArray();
                            })
                            ->required(),

                        // Input Jumlah Produk
                        TextInput::make('jumlah_produk')
                            ->label('Jumlah Produk')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->reactive()
                            ->debounce(1000)
                            ->afterStateUpdated(function (callable $get, callable $set, $state) {
                                $produkId = $get('produk_id');
                                $produk = Produk::find($produkId);
                                $harga = $produk ? $produk->harga : 0;
                                $set('subtotal', $harga * $state);
                            })
                            ,

                        // Subtotal, dihitung otomatis dan tidak bisa diubah langsung
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->disabled()
                            ->reactive(),
                    ])
                    ->columns(3)
                    ->minItems(1)
                    ->createItemButtonLabel('Tambah Produk')
                    // Secara default, repeater sudah mengizinkan edit dan hapus item
                    ->disableItemDeletion(false),

                // Total Harga, dihitung sebagai jumlah dari seluruh subtotal detail
                TextInput::make('total_harga')
                    ->label('Total Harga')
                    ->disabled()
                    ->reactive()
                    ->afterStateHydrated(function (callable $get, callable $set) {
                        $details = $get('detail') ?? [];
                        $total = collect($details)->sum(fn ($item) => Arr::get($item, 'subtotal', 0));
                        $set('total_harga', $total);
                    })
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $details = $get('detail') ?? [];
                        $total = collect($details)->sum(fn ($item) => Arr::get($item, 'subtotal', 0));
                        $set('total_harga', $total);
                    }),
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

    public static function afterSave($record, array $data): void
    {
        // Ambil ID pembelian dari salah satu record detail (asumsi semua detail milik pembelian yang sama)
        $pembelianId = $record->pembelian_id;

        // Hitung total harga berdasarkan data repeater yang disubmit (jika tersedia)
        $total = collect($data['detail'] ?? [])
            ->sum(fn ($item) => $item['subtotal'] ?? 0);

        // Update record pembelian
        \App\Models\Pembelian::find($pembelianId)->update(['total_harga' => $total]);
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
            'index' => Pages\ListDetailPembelians::route('/'),
            'create' => Pages\CreateDetailPembelian::route('/create'),
            'edit' => Pages\EditDetailPembelian::route('/{record}/edit'),
        ];
    }
}
