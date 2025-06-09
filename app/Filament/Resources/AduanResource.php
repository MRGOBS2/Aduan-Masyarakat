<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AduanResource\Pages;
use App\Filament\Resources\AduanResource\RelationManagers;
use App\Models\Aduan;
use App\Models\Kategori;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class AduanResource extends Resource
{
    protected static ?string $model = Aduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pelapor')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: function ($query) {
                            $query->whereHas('roles', function ($query) {
                                $query->where('name', 'masyarakat');
                            });
                        }
                    )
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->label('Judul Aduan'),
                Forms\Components\TextInput::make('lokasi')
                    ->required()
                    ->label('Lokasi'),
                Forms\Components\Textarea::make('isi_aduan')
                    ->required()
                    ->label('Isi Aduan'),
                Forms\Components\FileUpload::make('gambar_aduan')
                    ->disk('public')
                    ->image()
                    ->nullable()
                    ->label('Gambar Aduan'),
                Forms\Components\Radio::make('status')
                    ->options([
                        'diproses' => 'Diproses',
                        // 'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ])
                    ->default('diproses')
                    ->required()
                    ->disabled(),
                Forms\Components\DateTimePicker::make('tanggal_aduan')
                    ->required()
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('Pelapor'),
                Tables\Columns\TextColumn::make('kategori.nama')->label('Kategori'),
                ImageColumn::make('gambar_aduan')
                    ->disk('public')
                    ->label('Gambar Aduan')
                    ->visibility('visible')
                    ->height(60)
                    ->width(60)
                    ->size(50),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'diproses',
                        'danger' => 'ditolak',
                        'success' => 'selesai',
                    ])
                    ,
                Tables\Columns\TextColumn::make('tanggal_aduan')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            $cannotDelete = [];
                            $canDelete = [];

                            foreach ($records as $record) {
                                // Cek apakah aduan punya tanggapan
                                if ($record->tanggapan()->exists()) {
                                    $cannotDelete[] = $record->judul;
                                } else {
                                    $canDelete[] = $record;
                                }
                            }

                            // Hapus yang bisa dihapus
                            if (count($canDelete) > 0) {
                                foreach ($canDelete as $record) {
                                    $record->delete();
                                }

                                Notification::make()
                                    ->title('Berhasil menghapus ' . count($canDelete) . ' aduan')
                                    ->success()
                                    ->send();
                            }

                            // Notifikasi yang tidak bisa dihapus
                            if (count($cannotDelete) > 0) {
                                Notification::make()
                                    ->title('Beberapa aduan tidak dapat dihapus')
                                    ->body('Aduan berikut memiliki tanggapan dan tidak dapat dihapus: ' . implode(', ', $cannotDelete))
                                    ->warning()
                                    ->send();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListAduans::route('/'),
            'create' => Pages\CreateAduan::route('/create'),
            'edit' => Pages\EditAduan::route('/{record}/edit'),
        ];
    }
}
