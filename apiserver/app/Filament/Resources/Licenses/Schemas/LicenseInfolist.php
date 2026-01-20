<?php

namespace App\Filament\Resources\Licenses\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LicenseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product.title')
                    ->numeric(),
                TextEntry::make('product_resource_id')
                    ->numeric(),
                TextEntry::make('user.name')
                    ->numeric(),
                TextEntry::make('license_key'),
                TextEntry::make('license_type'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('usage_count')
                    ->numeric(),
                TextEntry::make('max_usage')
                    ->numeric(),
                TextEntry::make('expires_at')
                    ->dateTime(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('first_used_at')
                    ->dateTime(),
                TextEntry::make('last_used_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
