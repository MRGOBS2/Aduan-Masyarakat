<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Columns\ImageColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'User';
    protected static ?string $pluralModelLabel = 'User';
    protected static ?string $modelLabel = 'User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('kontak')
                    ->label('Kontak')
                    ->maxLength(12)
                    ->required(),
                Forms\Components\TextInput::make('alamat')
                    ->label('Alamat')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\Radio::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'laki-laki' => 'Laki-laki',
                        'perempuan' => 'Perempuan',
                    ])
                    ->inline()
                    ->required(),
                FileUpload::make('foto_profil')
                    ->label('Foto Profil')
                    ->image()
                    ->disk('public')
                    ->nullable()
                    ->imagePreviewHeight('150'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_profil')
                    ->label('Foto Profil')
                    ->circular()
                    ->height(40)
                    ->width(40),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Roles'),
                Tables\Columns\TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('kontak')->label('Kontak'),
                Tables\Columns\TextColumn::make('alamat')->label('Alamat'),
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
                                if ($record->aduan()->exists()) {
                                    $cannotDelete[] = $record->name;
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
                                    ->title('Berhasil menghapus ' . count($canDelete) . ' user')
                                    ->success()
                                    ->send();
                            }

                            // Notifikasi yang tidak bisa dihapus
                            if (count($cannotDelete) > 0) {
                                Notification::make()
                                    ->title('Beberapa user tidak dapat dihapus')
                                    ->body('User berikut memiliki aduan dan tidak dapat dihapus: ' . implode(', ', $cannotDelete))
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
