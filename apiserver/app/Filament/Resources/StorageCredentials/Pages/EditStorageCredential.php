<?php

namespace App\Filament\Resources\StorageCredentials\Pages;

use App\Filament\Resources\StorageCredentials\StorageCredentialResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStorageCredential extends EditRecord
{
    protected static string $resource = StorageCredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
