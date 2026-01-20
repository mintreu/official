<?php

namespace App\Filament\Resources\StorageProviders\Pages;

use App\Filament\Resources\StorageProviders\StorageProviderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStorageProvider extends EditRecord
{
    protected static string $resource = StorageProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
