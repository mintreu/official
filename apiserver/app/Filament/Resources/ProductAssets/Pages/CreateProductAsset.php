<?php

namespace App\Filament\Resources\ProductAssets\Pages;

use App\Filament\Resources\ProductAssets\ProductAssetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductAsset extends CreateRecord
{
    protected static string $resource = ProductAssetResource::class;
}
