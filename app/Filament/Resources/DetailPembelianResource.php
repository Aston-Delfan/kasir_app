<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use App\Models\DetailPembelian;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
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
        $pembelian = new Pembelian();
        $pembelianId = request('pembelian_id');
        if(request()->filled('pembelian_id'))
        $pembelian = Pembelian::findOrFail($pembelianId);
        return $form
            ->schema([
                TextInput::make('tanggal_pembelian')
                    ->label('Tanggal Pembelian')
                    ->disabled()
                    ->columnSpanFull()
                    ->default($pembelian->tanggal_pembelian),
                TextInput::make('supplier_nama')
                    ->label('Supplier')
                    ->disabled()
                    ->default($pembelian->supplier?->nama_perusahaan),
                TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->disabled()
                    ->default($pembelian->supplier?->nomor_telepon),
                Hidden::make('pembelian_id')
                    ->default($pembelian->id),
                Hidden::make('supplier_id')
                    ->default($pembelian->supplier_id),
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
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $produk = Produk::find($state);
                        $set('harga', $produk->harga);
                    }),
                TextInput::make('harga')
                    ->label('Harga')
                    ->disabled(),
                TextInput::make('jumlah_produk')
                    ->label('Jumlah Produk')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->reactive()
                    ->debounce(600)
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $harga = $get('harga');
                        if ($state) {
                            $set('subtotal', $harga * $state);
                        } else {
                            $set('subtotal', 0);
                        }
                    }),
                // Subtotal, dihitung otomatis dan tidak bisa diubah langsung
                TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->disabled()
                    ->reactive()
                    ->dehydrated(),
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
            'index' => Pages\ListDetailPembelians::route('/'),
            'create' => Pages\CreateDetailPembelian::route('/create'),
            'edit' => Pages\EditDetailPembelian::route('/{record}/edit'),
        ];
    }
}
