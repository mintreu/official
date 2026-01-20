<?php

namespace App\Filament\Resources\ProductAssets;

use App\Filament\Resources\ProductAssets\Pages\CreateProductAsset;
use App\Filament\Resources\ProductAssets\Pages\EditProductAsset;
use App\Filament\Resources\ProductAssets\Pages\ListProductAssets;
use App\Filament\Resources\ProductAssets\Pages\ViewProductAsset;
use App\Filament\Resources\ProductAssets\Schemas\ProductAssetForm;
use App\Filament\Resources\ProductAssets\Schemas\ProductAssetInfolist;
use App\Filament\Resources\ProductAssets\Tables\ProductAssetsTable;
use App\Models\ProductAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductAssetResource extends Resource
{
    protected static ?string $model = ProductAsset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProductAssetForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductAssetInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductAssetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductAssets::route('/'),
            'create' => CreateProductAsset::route('/create'),
            'view' => ViewProductAsset::route('/{record}'),
            'edit' => EditProductAsset::route('/{record}/edit'),
        ];
    }
}
