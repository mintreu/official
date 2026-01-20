<?php

namespace App\Filament\Resources\ProductAssets\Pages;

use App\Filament\Resources\ProductAssets\ProductAssetResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProductAsset extends ViewRecord
{
    protected static string $resource = ProductAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
