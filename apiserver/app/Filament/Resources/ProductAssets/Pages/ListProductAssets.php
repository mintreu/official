<?php

namespace App\Filament\Resources\ProductAssets\Pages;

use App\Filament\Resources\ProductAssets\ProductAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductAssets extends ListRecords
{
    protected static string $resource = ProductAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
