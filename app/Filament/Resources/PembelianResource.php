<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Supplier;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PembelianResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembelianResource\RelationManagers;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Field tanggal dengan default hari ini
                DatePicker::make('tanggal_pembelian')
                    ->label('Tanggal Pembelian')
                    ->default(now())
                    ->required(),
                // Select supplier dengan create option
                Select::make('supplier_id')
                    ->label('Pilih Supplier')
                    ->options(Supplier::all()->pluck('nama_perusahaan', 'id'))
                    ->searchable()
                    ->required()
                    ->debounce(100)
                    ->createOptionForm(SupplierResource::getForm())
                    ->createOptionUsing(fn (array $data): int => Supplier::create($data)->id)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $supplier = Supplier::find($state);
                        $set('nomor_telepon', $supplier->nomor_telepon ?? null);
                    }),
                // Nomor telepon, hanya untuk tampil, disabled
                TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_pembelian')
                    ->label('Tanggal'),
                TextColumn::make('supplier.nama_perusahaan')
                    ->label('Supplier'),
                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR'),
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
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}
