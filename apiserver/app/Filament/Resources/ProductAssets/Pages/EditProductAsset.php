<?php

namespace App\Filament\Resources\ProductAssets\Pages;

use App\Filament\Resources\ProductAssets\ProductAssetResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProductAsset extends EditRecord
{
    protected static string $resource = ProductAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
