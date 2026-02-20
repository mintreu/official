<?php

namespace App\Filament\Resources\ApiSpaces\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ApiSpaceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('product.slug')
                    ->label('API Project'),
                TextEntry::make('apiKey.key_prefix')
                    ->label('API Key'),
                TextEntry::make('website'),
                TextEntry::make('environment'),
                TextEntry::make('status'),
                TextEntry::make('requests_this_month'),
                TextEntry::make('requests_today'),
                TextEntry::make('last_request_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}

