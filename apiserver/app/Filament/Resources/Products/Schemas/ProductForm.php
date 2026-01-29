<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Casts\PublishableStatusCast;
use App\Enums\LicenseType;
use App\Enums\ProductType;
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
                                        TextInput::make('short_description')
                                            ->maxLength(160)
                                            ->helperText('Brief description for cards (max 160 chars)'),
                                    ]),

                                Fieldset::make('Product Configuration')
                                    ->columnSpanFull()
                                    ->columns(2)
                                    ->schema([
                                        Select::make('type')
                                            ->options(ProductType::class)
                                            ->required()
                                            ->live()
                                            ->helperText('Determines product behavior and download flow'),

                                        TextInput::make('category')
                                            ->helperText('e.g., frontend, backend, games, assets'),

                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->default(0.0)
                                            ->prefix('$')
                                            ->helperText('Set 0 for free products'),

                                        Select::make('default_license')
                                            ->options(LicenseType::class)
                                            ->required()
                                            ->default(LicenseType::FreeAttribution)
                                            ->helperText('License type for new downloads'),

                                        Select::make('status')
                                            ->options(PublishableStatusCast::class)
                                            ->default('draft')
                                            ->required(),

                                        Toggle::make('requires_auth')
                                            ->label('Requires Login')
                                            ->helperText('Users must log in to download'),

                                        Toggle::make('featured')
                                            ->helperText('Show on homepage'),
                                    ]),

                                Section::make('Description')
                                    ->columnSpanFull()
                                    ->schema([
                                        Textarea::make('description')
                                            ->columnSpanFull()
                                            ->rows(3),
                                    ]),

                            ]),
                        Tab::make('Content')
                            ->schema([
                                RichEditor::make('content')
                                    ->columnSpanFull()
                                    ->helperText('Full product description with features, installation guide, etc.'),
                            ]),
                        Tab::make('Links & Metadata')
                            ->columns(2)
                            ->schema([
                                TextInput::make('demo_url')
                                    ->url()
                                    ->label('Demo URL'),
                                TextInput::make('github_url')
                                    ->url()
                                    ->label('GitHub URL')
                                    ->helperText('Public repo for freebies, backlinks'),
                                TextInput::make('documentation_url')
                                    ->url()
                                    ->label('Documentation URL'),
                                TextInput::make('version')
                                    ->helperText('e.g., 1.0.0'),
                                TextInput::make('downloads')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->helperText('Auto-tracked'),
                                TextInput::make('rating')
                                    ->numeric()
                                    ->default(0.0)
                                    ->disabled()
                                    ->helperText('Auto-calculated'),
                            ]),
                    ]),
            ]);
    }
}
