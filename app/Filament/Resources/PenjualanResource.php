<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pelanggan;
use Filament\Forms\Form;
use App\Models\Penjualan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PenjualanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PenjualanResource\RelationManagers;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $label = 'Data Penjualan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Field tanggal dengan default hari ini
                DatePicker::make('tanggal_penjualan')
                    ->label('Tanggal Penjualan')
                    ->default(now())
                    ->required()
                    ->columnSpanFull(),
                // Select pelanggan dengan create option
                Select::make('pelanggan_id')
                    ->label('Pilih Pelanggan')
                    ->options(Pelanggan::all()->pluck('nama_pelanggan', 'id'))
                    ->searchable()
                    ->nullable()
                    ->debounce(600)
                    ->createOptionForm(PelangganResource::getForm())
                    ->createOptionUsing(fn (array $data): int => Pelanggan::create($data)->id)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $pelanggan = Pelanggan::find($state);
                        $set('nomor_telepon', $pelanggan->nomor_telepon ?? null);
                    })
                    ->default("-"),
                // Nomor telepon, hanya untuk tampil, disabled
                TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->disabled()
                    ->nullable()
                    ->default("-"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_penjualan')
                    ->label('Tanggal')
                    ->dateTime('d F Y'),
                TextColumn::make('pelanggan.nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->default('-'),
                TextColumn::make('pelanggan.nomor_telepon')
                    ->label('Nomor Telepon')
                    ->default('-'),

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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
