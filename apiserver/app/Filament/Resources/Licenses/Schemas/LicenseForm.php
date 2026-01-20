<?php

namespace App\Filament\Resources\Licenses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LicenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'title')
                    ->required(),
                TextInput::make('product_resource_id')
                    ->numeric(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('license_key')
                    ->required(),
                TextInput::make('license_type')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('usage_terms'),
                TextInput::make('attribution_text'),
                TextInput::make('usage_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('max_usage')
                    ->numeric(),
                TextInput::make('api_config'),
                DateTimePicker::make('expires_at'),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('first_used_at'),
                DateTimePicker::make('last_used_at'),
            ]);
    }
}
