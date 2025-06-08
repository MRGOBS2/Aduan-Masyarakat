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
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
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
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'ditolak' => 'Ditolak',
                        'selesai' => 'Selesai',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\DateTimePicker::make('tanggal_aduan')->required(),
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
                Tables\Columns\TextColumn::make('status')->badge(),
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
            'index' => Pages\ListAduans::route('/'),
            'create' => Pages\CreateAduan::route('/create'),
            'edit' => Pages\EditAduan::route('/{record}/edit'),
        ];
    }
}
