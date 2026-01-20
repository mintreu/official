<?php

namespace App\Filament\Resources\Advertisements\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AdvertisementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('placement')
                    ->required(),
                Textarea::make('html_code')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('allowed_pages'),
                TextInput::make('priority')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('impressions')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('clicks')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('unique_ips')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('viewed_ips'),
                TextInput::make('max_impressions_per_ip')
                    ->required()
                    ->numeric()
                    ->default(3),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('starts_at'),
                DateTimePicker::make('ends_at'),
            ]);
    }
}
