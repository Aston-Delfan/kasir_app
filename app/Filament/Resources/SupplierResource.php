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
use Filament\Forms\Components\TextArea;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SupplierResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SupplierResource\RelationManagers;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $label = 'Data Supplier';

    public static function getForm(){
        return[
            TextInput::make('nama_perusahaan')
                ->label('Nama Perusahaan'),
            TextInput::make('nama_kontak')
                ->label('Nama kontak')
                ->minLength(2)
                ->required(),
            TextInput::make('nomor_telepon')
                ->label('Nomor Telepon')
                ->tel()
                ->unique(ignoreRecord: true)
                ->required(),
            TextInput::make('email')
                ->label('Email'),
            Select::make('produk_id')
                ->label('Pilih Produk')
                ->multiple()
                ->searchable()
                ->preload()
                ->createOptionForm(ProdukResource::getForm())
                ->createOptionUsing(fn (array $data): int => Produk::create($data)->id)
                ->relationship('produks', 'nama_produk')
                // ->options(Produk::all()
                // ->pluck('nama_produk', 'id'))
                // ->createOptionForm(ProdukResource::getForm())
                // ->createOptionUsing(function (array $data): int {
                //     return Produk::create($data)->id;
                // }),
                ,
            TextArea::make('alamat')
                ->label('Alamat')
                ->columnSpanFull(),
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
                TextColumn::make('nama_perusahaan')
                    ->searchable(),
                TextColumn::make('nama_kontak')
                    ->searchable(),
                TextColumn::make('nomor_telepon')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('alamat')
                    ->searchable(),
                TagsColumn::make('produks.nama_produk')
                    ->label('Produk')
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
