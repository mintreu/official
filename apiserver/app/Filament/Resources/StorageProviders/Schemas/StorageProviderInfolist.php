<?php

namespace App\Filament\Resources\StorageProviders\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StorageProviderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('display_name'),
                TextEntry::make('icon'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('rate_limit')
                    ->numeric(),
                TextEntry::make('webhook_secret'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
