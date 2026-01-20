<?php

namespace App\Filament\Resources\StorageCredentialResource\Pages;

use App\Filament\Resources\StorageCredentialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStorageCredentials extends ListRecords
{
    protected static string $resource = StorageCredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
