<?php

namespace App\Filament\Resources\ApiSpaces\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ApiSpaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Select::make('api_key_id')
                    ->relationship('apiKey', 'key_prefix')
                    ->searchable()
                    ->required(),
                Select::make('product_id')
                    ->relationship('product', 'title')
                    ->searchable(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('website')
                    ->required()
                    ->url(),
                Select::make('environment')
                    ->options([
                        'prod' => 'Production',
                        'staging' => 'Staging',
                        'dev' => 'Development',
                    ])
                    ->default('prod')
                    ->required(),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'paused' => 'Paused',
                    ])
                    ->default('active')
                    ->required(),
                TextInput::make('requests_this_month')
                    ->numeric()
                    ->default(0),
                TextInput::make('requests_today')
                    ->numeric()
                    ->default(0),
                KeyValue::make('config')
                    ->columnSpanFull(),
                KeyValue::make('insights')
                    ->columnSpanFull(),
            ]);
    }
}

