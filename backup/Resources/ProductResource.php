<?php

namespace App\Filament\Resources;

use App\Enums\LicenseType;
use App\Models\Product;
use App\Models\StorageProvider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Products';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Tabs::make('Product')
                    ->tabs([
                        Forms\Tabs\Tab::make('General')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', str($state)->slug())),

                                Forms\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),

                                Forms\Select::make('type')
                                    ->label('Product Type')
                                    ->options([
                                        'template' => 'Template',
                                        'api' => 'API',
                                        'package' => 'Code Package',
                                        'media' => 'Media (Fonts, Icons, etc)',
                                    ])
                                    ->required()
                                    ->reactive(),

                                Forms\Select::make('category')
                                    ->options([
                                        'design' => 'Design',
                                        'development' => 'Development',
                                        'frontend' => 'Frontend',
                                        'backend' => 'Backend',
                                        'fullstack' => 'Fullstack',
                                        'mobile' => 'Mobile',
                                        'icons' => 'Icons',
                                        'fonts' => 'Fonts',
                                        'images' => 'Images',
                                        'other' => 'Other',
                                    ])
                                    ->required(),

                                Forms\Textarea::make('description')
                                    ->required()
                                    ->maxLength(500)
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Forms\RichEditor::make('content')
                                    ->label('Detailed Content')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),

                                Forms\FileUpload::make('image')
                                    ->image()
                                    ->directory('products')
                                    ->maxSize(5120),
                            ])
                            ->columns(2),

                        Forms\Tabs\Tab::make('Pricing & License')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Forms\Toggle::make('is_payable')
                                    ->label('This is a Paid Product')
                                    ->reactive()
                                    ->default(false)
                                    ->helperText('Enable if this product requires payment'),

                                Forms\Decimal::make('price')
                                    ->visible(fn (Forms\Get $get) => $get('is_payable'))
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->required(fn (Forms\Get $get) => $get('is_payable')),

                                Forms\Toggle::make('requires_account')
                                    ->label('Require Login to Download')
                                    ->helperText('Track downloads by requiring user account'),

                                Forms\Select::make('default_license_type')
                                    ->label('Default License Type')
                                    ->options(collect(LicenseType::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()]))
                                    ->helperText('License type assigned to free downloads')
                                    ->required(),
                            ])
                            ->columns(2),

                        Forms\Tabs\Tab::make('Download Sources')
                            ->icon('heroicon-o-cloud-arrow-down')
                            ->schema([
                                Forms\Repeater::make('configs')
                                    ->relationship()
                                    ->label('Download Sources')
                                    ->schema([
                                        Forms\Select::make('source_type')
                                            ->label('Storage Type')
                                            ->options(collect(StorageProvider::active()->get())->mapWithKeys(fn ($p) => [$p->name => $p->display_name]))
                                            ->required()
                                            ->reactive(),

                                        Forms\Select::make('storage_credential_id')
                                            ->label('Credentials')
                                            ->relationship('storageCredential', 'name')
                                            ->preload()
                                            ->nullable()
                                            ->helperText('Required for private repositories'),

                                        Forms\TextInput::make('source_identifier')
                                            ->label('Source Path/URL/ID')
                                            ->required()
                                            ->helperText('Repo path, folder ID, or direct URL')
                                            ->columnSpanFull(),

                                        Forms\Textarea::make('metadata')
                                            ->label('Additional Configuration (JSON)')
                                            ->json()
                                            ->columnSpanFull()
                                            ->helperText('Provider-specific settings like release tag, API keys, etc'),

                                        Forms\Toggle::make('is_primary')
                                            ->label('Primary Download Source')
                                            ->default(true)
                                            ->helperText('Default source for downloads'),

                                        Forms\Toggle::make('is_private')
                                            ->label('Private Access')
                                            ->helperText('Requires authentication via credentials'),
                                    ])
                                    ->createItemButtonLabel('Add Download Source')
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Tabs\Tab::make('Product Resources')
                            ->icon('heroicon-o-archive-box')
                            ->schema([
                                Forms\Repeater::make('resources')
                                    ->relationship()
                                    ->label('Downloadable Resources')
                                    ->schema([
                                        Forms\TextInput::make('name')
                                            ->label('Resource Name')
                                            ->placeholder('e.g., Source Code, Binary, Demo')
                                            ->required(),

                                        Forms\Textarea::make('description')
                                            ->maxLength(500)
                                            ->rows(2),

                                        Forms\Select::make('resource_type')
                                            ->options([
                                                'MAIN' => 'Main Download',
                                                'DOCUMENTATION' => 'Documentation',
                                                'DEMO' => 'Demo/Preview',
                                                'MEDIA' => 'Additional Media',
                                                'SOURCE' => 'Source Code',
                                            ])
                                            ->required(),

                                        Forms\Select::make('download_limit')
                                            ->label('Download Limit')
                                            ->options([
                                                null => 'Unlimited',
                                                '1' => '1 Time',
                                                '3' => '3 Times',
                                                '5' => '5 Times',
                                                '10' => '10 Times',
                                            ])
                                            ->default(null),

                                        Forms\Toggle::make('requires_auth')
                                            ->label('Requires Login'),

                                        Forms\Toggle::make('is_commercial_only')
                                            ->label('Commercial License Only')
                                            ->helperText('Only available with commercial licenses'),
                                    ])
                                    ->createItemButtonLabel('Add Resource')
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Tabs\Tab::make('SEO & Publishing')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                Forms\TextInput::make('version')
                                    ->default('1.0.0'),

                                Forms\Toggle::make('featured')
                                    ->label('Featured Product'),

                                Forms\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'archived' => 'Archived',
                                    ])
                                    ->required()
                                    ->default('draft'),

                                Forms\TextInput::make('demo_url')
                                    ->label('Demo/Preview URL')
                                    ->url(),

                                Forms\TextInput::make('github_url')
                                    ->label('GitHub URL')
                                    ->url(),

                                Forms\TextInput::make('documentation_url')
                                    ->label('Documentation URL')
                                    ->url(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->colors([
                        'blue' => 'template',
                        'green' => 'package',
                        'purple' => 'api',
                        'orange' => 'media',
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                        'gray' => 'archived',
                    ]),

                Tables\Columns\BadgeColumn::make('is_payable')
                    ->label('Type')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Paid' : 'Free')
                    ->colors([
                        'success' => false,
                        'warning' => true,
                    ]),

                Tables\Columns\TextColumn::make('price')
                    ->visible(fn () => true)
                    ->money('USD')
                    ->formatStateUsing(fn ($state, $record) => $record->is_payable ? '$'.$state : 'Free'),

                Tables\Columns\TextColumn::make('downloads')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'template' => 'Template',
                        'api' => 'API',
                        'package' => 'Code Package',
                        'media' => 'Media',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),

                Tables\Filters\TernaryFilter::make('is_payable')
                    ->label('Paid Products'),

                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ProductResource\Pages\ListProducts::class,
            'create' => \App\Filament\Resources\ProductResource\Pages\CreateProduct::class,
            'edit' => \App\Filament\Resources\ProductResource\Pages\EditProduct::class,
        ];
    }
}
