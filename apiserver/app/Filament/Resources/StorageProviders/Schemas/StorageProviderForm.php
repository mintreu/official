<?php

namespace App\Filament\Resources\StorageProviders\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StorageProviderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('display_name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('icon'),
                TextInput::make('config_schema')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('rate_limit')
                    ->numeric(),
                TextInput::make('webhook_secret'),
            ]);
    }
}
