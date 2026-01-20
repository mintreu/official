<?php

namespace App\Filament\Resources;

use App\Models\StorageProvider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StorageProviderResource extends Resource
{
    protected static ?string $model = StorageProvider::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    protected static ?string $navigationLabel = 'Storage Providers';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->hint('System identifier: GITHUB, BITBUCKET, AWS_S3, etc'),

                Forms\TextInput::make('display_name')
                    ->required()
                    ->maxLength(100)
                    ->hint('Human readable name shown in admin panel'),

                Forms\Textarea::make('description')
                    ->maxLength(500)
                    ->rows(3),

                Forms\TextInput::make('icon')
                    ->maxLength(50)
                    ->hint('Icon class or URL'),

                Forms\Textarea::make('config_schema')
                    ->json()
                    ->required()
                    ->columnSpanFull()
                    ->helperText('JSON schema defining required fields for credentials'),

                Forms\Toggle::make('is_active')
                    ->default(true),

                Forms\TextInput::make('rate_limit')
                    ->numeric()
                    ->minValue(0)
                    ->nullable()
                    ->helperText('API calls per hour (NULL = unlimited)'),

                Forms\TextInput::make('webhook_secret')
                    ->password()
                    ->nullable()
                    ->helperText('Secret for validating webhook requests'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('display_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rate_limit')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\StorageProviderResource\Pages\ListStorageProviders::class,
            'create' => \App\Filament\Resources\StorageProviderResource\Pages\CreateStorageProvider::class,
            'edit' => \App\Filament\Resources\StorageProviderResource\Pages\EditStorageProvider::class,
        ];
    }
}
