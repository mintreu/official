<?php

namespace App\Filament\Resources\StorageCredentials\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StorageCredentialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('storageProvider.name')
                    ->numeric(),
                TextEntry::make('account_identifier'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('last_verified_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
