<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Casts\PublishableStatusCast;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('category'),
                TextInput::make('type'),
                TextInput::make('download_url'),
                TextInput::make('demo_url'),
                TextInput::make('github_url'),
                TextInput::make('documentation_url'),
                TextInput::make('version'),
                TextInput::make('downloads')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options(PublishableStatusCast::class)
                    ->default('draft')
                    ->required(),
                Toggle::make('featured')
                    ->required(),
                Toggle::make('is_payable')
                    ->required(),
                Toggle::make('requires_account')
                    ->required(),
                TextInput::make('default_license_type')
                    ->required()
                    ->default('FREE_ATTRIBUTION'),
            ]);
    }
}
