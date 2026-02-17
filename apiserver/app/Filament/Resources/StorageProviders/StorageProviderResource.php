<?php

namespace App\Filament\Resources\StorageProviders;

use App\Filament\Resources\StorageProviders\Pages\CreateStorageProvider;
use App\Filament\Resources\StorageProviders\Pages\EditStorageProvider;
use App\Filament\Resources\StorageProviders\Pages\ListStorageProviders;
use App\Filament\Resources\StorageProviders\Pages\ViewStorageProvider;
use App\Filament\Resources\StorageProviders\Schemas\StorageProviderForm;
use App\Filament\Resources\StorageProviders\Schemas\StorageProviderInfolist;
use App\Filament\Resources\StorageProviders\Tables\StorageProvidersTable;
use App\Models\StorageProvider;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StorageProviderResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = StorageProvider::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return StorageProviderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StorageProviderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StorageProvidersTable::configure($table);
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
            'index' => ListStorageProviders::route('/'),
            'create' => CreateStorageProvider::route('/create'),
            'view' => ViewStorageProvider::route('/{record}'),
            'edit' => EditStorageProvider::route('/{record}/edit'),
        ];
    }
}
