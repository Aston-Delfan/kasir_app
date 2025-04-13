<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use App\Models\Category;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\ProdukResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProdukResource\RelationManagers;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $label = 'Data Produk';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $navigationGroup = 'Manajemen Produk';

    public static function getForm(){
        return[
            Section::make('Informasi Produk')
                ->schema([
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
                        ->disabledOn('edit')
                        ->numeric()
                        ->required(),
                    TextInput::make('harga')
                        ->label('Harga')
                        ->numeric()
                        ->prefix('Rp ')
                        ->required(),
                    TextInput::make('barcode')
                        ->label('Kode Barcode')
                        ->helperText('Jika dikosongkan, barcode akan digenerate otomatis')
                        ->maxLength(255)
                        ->unique(ignorable: fn ($record) => $record),
                ])->columns(2),

            // Hanya tampilkan barcode pada halaman edit
            Placeholder::make('barcode_preview')
                ->label('Preview Barcode')
                ->content(function ($record) {
                    if (!$record || !$record->exists || !$record->barcode) {
                        return 'Barcode akan muncul setelah produk disimpan';
                    }

                    return new \Illuminate\Support\HtmlString($record->getBarcodeHtml());
                })
                ->hiddenOn('create'),
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
                TextColumn::make('barcode')
                    ->label('Kode Barcode')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),
                Tables\Columns\ViewColumn::make('barcode')
                    ->label('Barcode')
                    ->view('tables.columns.barcode_column')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'nama_kategori')
                    ->label('Kategori'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print_barcode')
                    ->label('Cetak Barcode')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Produk $record) => route('barcode.print', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                                        Tables\Actions\BulkAction::make('print_barcodes')
                        ->label('Cetak Barcode')
                        ->icon('heroicon-o-printer')
                        ->action(function (array $records) {
                            $ids = collect($records)->pluck('id')->toArray();
                            return redirect()->route('barcode.print-bulk', ['ids' => implode(',', $ids)]);
                        })
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
