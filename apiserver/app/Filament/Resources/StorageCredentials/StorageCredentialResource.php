<?php

namespace App\Filament\Resources\StorageCredentials;

use App\Filament\Resources\StorageCredentials\Pages\CreateStorageCredential;
use App\Filament\Resources\StorageCredentials\Pages\EditStorageCredential;
use App\Filament\Resources\StorageCredentials\Pages\ListStorageCredentials;
use App\Filament\Resources\StorageCredentials\Pages\ViewStorageCredential;
use App\Filament\Resources\StorageCredentials\Schemas\StorageCredentialForm;
use App\Filament\Resources\StorageCredentials\Schemas\StorageCredentialInfolist;
use App\Filament\Resources\StorageCredentials\Tables\StorageCredentialsTable;
use App\Models\StorageCredential;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StorageCredentialResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = StorageCredential::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return StorageCredentialForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StorageCredentialInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StorageCredentialsTable::configure($table);
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
            'index' => ListStorageCredentials::route('/'),
            'create' => CreateStorageCredential::route('/create'),
            'view' => ViewStorageCredential::route('/{record}'),
            'edit' => EditStorageCredential::route('/{record}/edit'),
        ];
    }
}
