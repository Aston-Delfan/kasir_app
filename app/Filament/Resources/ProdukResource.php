<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProdukResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProdukResource\RelationManagers;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $label = 'Data Produk';

    public static function getForm(){
        return[
            TextInput::make('nama_produk')
                ->label('Nama Produk')
                ->required(),
            Select::make('kategori')
                ->label('Kategori')
                ->options([
                    'operator'=> 'Operator',
                    'admin'=> 'Admin',
                ])
                ->default('operator'),
            Select::make('supplier_id')
                ->label('Pilih Supplier')
                ->multiple()
                ->options(Supplier::all()->pluck('nama_perusahaan', 'id'))
                ->relationship('suppliers', 'nama_perusahaan')
                ->searchable()
                // ->createOptionForm(SupplierResource::getForm())
                // ->createOptionUsing(function (array $data): int {
                //     return Supplier::create($data)->id;
                // }),
                ,
            TextInput::make('stok')
                ->label('Stok Awal')
                ->disabledOn('edit'),
            TextInput::make('harga')
                ->label('Harga')
                ->numeric()
                ->prefix('Rp ')
                ->required(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                self::getForm()
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_produk')
                    ->searchable(),
                TextColumn::make('kategori')
                    ->searchable(),
                TextColumn::make('stok')
                    ->searchable(),
                TextColumn::make('harga')
                    ->searchable(),
                TagsColumn::make('suppliers.nama_perusahaan')
                    ->label('Supplier')
                    ->separator(', '),
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
