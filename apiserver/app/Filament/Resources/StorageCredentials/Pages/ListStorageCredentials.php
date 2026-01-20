<?php

namespace App\Filament\Resources\StorageCredentials\Pages;

use App\Filament\Resources\StorageCredentials\StorageCredentialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStorageCredentials extends ListRecords
{
    protected static string $resource = StorageCredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
