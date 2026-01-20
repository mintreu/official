<?php

namespace App\Filament\Resources\Advertisements\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AdvertisementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('placement'),
                TextEntry::make('priority')
                    ->numeric(),
                TextEntry::make('impressions')
                    ->numeric(),
                TextEntry::make('clicks')
                    ->numeric(),
                TextEntry::make('unique_ips')
                    ->numeric(),
                TextEntry::make('max_impressions_per_ip')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('starts_at')
                    ->dateTime(),
                TextEntry::make('ends_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
