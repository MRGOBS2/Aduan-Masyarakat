<?php

namespace App\Filament\Resources\TanggapanResource\Pages;

use App\Filament\Resources\TanggapanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTanggapan extends EditRecord
{
    protected static string $resource = TanggapanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
