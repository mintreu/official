<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Casts\PublishableStatusCast;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Tabs::make('Tabs')
                    ->contained(false)
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('General')
                            ->columns(3)
                            ->schema([
                                FileUpload::make('image')
                                    ->image(),

                                Fieldset::make('Info')
                                    ->columnSpan(2)
                                    ->columns(1)
                                    ->schema([
                                        TextInput::make('title')
                                            ->live()
                                            ->afterStateUpdated(fn ($state, Set $set) => $set('slug', Str::slug($state)))
                                            ->required(),
                                        TextInput::make('slug')
                                            ->required(),

                                    ]),

                                Fieldset::make('General Configuration')
                                    ->columnSpanFull()
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('is_payable')
                                            ->live()
                                            ->required(),

                                        TextInput::make('category'),

                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->visible(fn (Get $get) => $get('is_payable'))
                                            ->default(0.0)
                                            ->prefix('INR'),

                                        TextInput::make('type'),

                                        Select::make('status')
                                            ->options(PublishableStatusCast::class)
                                            ->default('draft')
                                            ->required(),

                                        Toggle::make('requires_account')
                                            ->required(),
                                    ]),

                                Section::make('Description')
                                    ->columnSpanFull()
                                    ->schema([
                                        RichEditor::make('description')
                                            ->columnSpanFull(),
                                    ]),

                            ]),
                        Tab::make('Tab 2')
                            ->schema([
                                // ...
                            ]),
                        Tab::make('Tab 3')
                            ->schema([
                                // ...
                            ]),
                    ]),

                Textarea::make('content')
                    ->columnSpanFull(),

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

                Toggle::make('featured')
                    ->required(),

                TextInput::make('default_license_type')
                    ->required()
                    ->default('FREE_ATTRIBUTION'),
            ]);
    }
}
