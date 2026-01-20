<?php

namespace App\Filament\Resources\StorageCredentials\Pages;

use App\Filament\Resources\StorageCredentials\StorageCredentialResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStorageCredential extends ViewRecord
{
    protected static string $resource = StorageCredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
