<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Supplier;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
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

    protected static ?string $label = 'Data Pembelian';
    protected static ?string $navigationLabel = 'Pembelian';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
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
                                if ($state) {
                                    $supplier = Supplier::find($state);
                                    $set('nomor_telepon', $supplier->nomor_telepon ?? null);
                                }
                            })
                            ->afterStateHydrated(function ($state, callable $set) {
                                if ($state) {
                                    $supplier = Supplier::find($state);
                                    $set('nomor_telepon', $supplier->nomor_telepon ?? null);
                                }
                            }),
                        // Nomor telepon, hanya untuk tampil, disabled
                        TextInput::make('nomor_telepon')
                            ->label('Nomor Telepon')
                            ->disabled(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier.nama_perusahaan')
                    ->label('Nama Supplier'),
                TextColumn::make('supplier.nama_kontak')
                    ->label('Nama Kontak'),
                TextColumn::make('tanggal_pembelian')
                    ->label('Tanggal')
                    ->dateTime('d F Y'),
                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('idr')
                    ->default('-'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Pembelian $record) => route('reports.pembelian', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('print')
                        ->label('Cetak Pembelian Terpilih')
                        ->icon('heroicon-o-printer')
                        ->action(function (Tables\Actions\BulkAction $action, array $data): void {
                            // Redirect to a custom route with the selected IDs
                            $ids = collect($data)->keys()->join(',');
                            $url = route('reports.pembelian.bulk', ['ids' => $ids]);
                            $action->redirect($url);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DetailPembeliansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
            'view' => Pages\ViewPembelian::route('/{record}'),
        ];
    }
}
