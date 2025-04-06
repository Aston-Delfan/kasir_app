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
use App\Models\Category;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $label = 'Data Produk';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $navigationGroup = 'Manajemen Produk';

    public static function getForm(){
        return[
            TextInput::make('nama_produk')
                ->label('Nama Produk')
                ->required(),
            Select::make('category_id')
                ->label('Kategori')
                ->relationship('category', 'nama_kategori')
                ->createOptionForm([
                    Forms\Components\TextInput::make('nama_kategori')
                        ->label('Nama Kategori')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('deskripsi')
                        ->label('Deskripsi')
                        ->maxLength(255),
                ])
                ->createOptionUsing(fn (array $data) => Category::create($data)->id)
                ->searchable()
                ->preload()
                ->required(),
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
                    TextColumn::make('category.nama_kategori')
                    ->label('Kategori')
                    ->searchable(),
                TextColumn::make('stok')
                    ->searchable(),
                TextColumn::make('harga')
                    ->searchable(),
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
