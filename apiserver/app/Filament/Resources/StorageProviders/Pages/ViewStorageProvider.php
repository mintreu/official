<?php

namespace App\Filament\Resources\StorageProviders\Pages;

use App\Filament\Resources\StorageProviders\StorageProviderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStorageProvider extends ViewRecord
{
    protected static string $resource = StorageProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
