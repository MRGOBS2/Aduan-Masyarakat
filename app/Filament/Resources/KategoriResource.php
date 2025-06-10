<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriResource\Pages;
use App\Filament\Resources\KategoriResource\RelationManagers;
use App\Models\Kategori;
use App\Models\Aduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';
    protected static ?string $modelLabel = 'Kategori';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')->required()->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')->limit(50),
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
                                // Cek apakah kategori punya aduan
                                if ($record->aduan()->exists()) {
                                    $cannotDelete[] = $record->nama;
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
                                    ->title('Berhasil menghapus ' . count($canDelete) . ' kategori')
                                    ->success()
                                    ->send();
                            }

                            // Notifikasi yang tidak bisa dihapus
                            if (count($cannotDelete) > 0) {
                                Notification::make()
                                    ->title('Beberapa kategori tidak dapat dihapus')
                                    ->body('Kategori berikut memiliki aduan dan tidak dapat dihapus: ' . implode(', ', $cannotDelete))
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
            'index' => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }
}
