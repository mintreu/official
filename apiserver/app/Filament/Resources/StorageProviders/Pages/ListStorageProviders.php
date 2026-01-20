<?php

namespace App\Filament\Resources\StorageProviders\Pages;

use App\Filament\Resources\StorageProviders\StorageProviderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStorageProviders extends ListRecords
{
    protected static string $resource = StorageProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
