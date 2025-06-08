<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TanggapanResource\Pages;
use App\Filament\Resources\TanggapanResource\RelationManagers;
use App\Models\Tanggapan;
use App\Models\User;
use App\Models\Aduan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TanggapanResource extends Resource
{
    protected static ?string $model = Tanggapan::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('aduan_id')
                    ->label('Aduan')
                    ->relationship('aduan', 'judul')
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->label('Penanggap')
                    ->relationship('user', 'name')
                    ->required(),

                Forms\Components\Textarea::make('isi_tanggapan')->required(),
                Forms\Components\DateTimePicker::make('tanggal_tanggapan')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aduan.judul')->label('Aduan'),
                Tables\Columns\TextColumn::make('user.name')->label('Penanggap'),
                Tables\Columns\TextColumn::make('isi_tanggapan')->limit(40),
                Tables\Columns\TextColumn::make('tanggal_tanggapan')->dateTime(),
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
            'index' => Pages\ListTanggapans::route('/'),
            'create' => Pages\CreateTanggapan::route('/create'),
            'edit' => Pages\EditTanggapan::route('/{record}/edit'),
        ];
    }
}
