<?php

namespace App\Filament\Resources\StorageCredentials\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StorageCredentialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('storage_provider_id')
                    ->relationship('storageProvider', 'name')
                    ->required(),
                TextInput::make('account_identifier')
                    ->required(),
                TextInput::make('metadata'),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('last_verified_at'),
            ]);
    }
}
