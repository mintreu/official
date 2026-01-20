<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('slug'),
                TextEntry::make('title'),
                ImageEntry::make('image'),
                TextEntry::make('price')
                    ->money(),
                TextEntry::make('category'),
                TextEntry::make('type'),
                TextEntry::make('download_url'),
                TextEntry::make('demo_url'),
                TextEntry::make('github_url'),
                TextEntry::make('documentation_url'),
                TextEntry::make('version'),
                TextEntry::make('downloads')
                    ->numeric(),
                TextEntry::make('rating')
                    ->numeric(),
                TextEntry::make('status'),
                IconEntry::make('featured')
                    ->boolean(),
                IconEntry::make('is_payable')
                    ->boolean(),
                IconEntry::make('requires_account')
                    ->boolean(),
                TextEntry::make('default_license_type'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
