<?php

namespace App\Filament\App\Resources\Studio\StudioResource\Pages;

use App\Filament\App\Resources\Studio\StudioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudio extends EditRecord
{
    protected static string $resource = StudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
