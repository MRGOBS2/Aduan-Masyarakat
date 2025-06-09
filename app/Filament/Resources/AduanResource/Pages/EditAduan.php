<?php

namespace App\Filament\Resources\AduanResource\Pages;

use App\Filament\Resources\AduanResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use App\Models\Aduan;

class EditAduan extends EditRecord
{
    protected static string $resource = AduanResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Pelapor')
                ->relationship('user', 'name')
                ->disabled()
                ->required(),

            Forms\Components\Select::make('kategori_id')
                ->label('Kategori')
                ->relationship('kategori', 'nama')
                ->disabled()
                ->required(),

            Forms\Components\TextInput::make('judul')
                ->label('Judul Aduan')
                ->disabled()
                ->required(),

            Forms\Components\TextInput::make('lokasi')
                ->label('Lokasi')
                ->disabled()
                ->required(),

            Forms\Components\Textarea::make('isi_aduan')
                ->label('Isi Aduan')
                ->disabled()
                ->required(),

            Forms\Components\FileUpload::make('gambar_aduan')
                ->image()
                ->label('Gambar Aduan')
                ->directory('aduan')
                ->disabled()
                ->nullable(),

            Forms\Components\Radio::make('status')
                ->options([
                    'ditolak' => 'Ditolak',
                ])
                ->disabled(fn (?Aduan $record) => in_array($record?->status, ['selesai', 'ditolak'])),

            Forms\Components\DateTimePicker::make('tanggal_aduan')
                ->label('Tanggal Aduan')
                ->disabled()
                ->required(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
