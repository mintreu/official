<?php

namespace App\Filament\Resources;

use App\Models\StorageCredential;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Crypt;

class StorageCredentialResource extends Resource
{
    protected static ?string $model = StorageCredential::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'Storage Credentials';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\TextInput::make('name')
                    ->required()
                    ->maxLength(100)
                    ->helperText('Friendly name for this credential set'),

                Forms\Select::make('storage_provider_id')
                    ->label('Storage Provider')
                    ->relationship('storageProvider', 'display_name')
                    ->required()
                    ->preload()
                    ->reactive(),

                Forms\TextInput::make('account_identifier')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Account username, ID, or identifier'),

                Forms\TextInput::make('encrypted_token')
                    ->label('API Token/Key')
                    ->required()
                    ->password()
                    ->dehydrateStateUsing(function (?string $state) {
                        return $state ? Crypt::encryptString($state) : null;
                    })
                    ->helperText('Will be encrypted and stored securely'),

                Forms\Textarea::make('metadata')
                    ->json()
                    ->nullable()
                    ->columnSpanFull()
                    ->helperText('Additional provider-specific configuration in JSON format'),

                Forms\Toggle::make('is_active')
                    ->default(true)
                    ->helperText('Disable temporarily without deleting'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('storageProvider.display_name')
                    ->label('Provider')
                    ->sortable(),

                Tables\Columns\TextColumn::make('account_identifier')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_verified_at')
                    ->label('Last Verified')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('storage_provider_id')
                    ->label('Provider')
                    ->relationship('storageProvider', 'display_name'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (StorageCredential $record) {
                        $record->update(['last_verified_at' => now()]);
                        // TODO: Implement actual verification logic
                    }),
                Tables\Actions\DeleteAction::make(),
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
            'index' => \App\Filament\Resources\StorageCredentialResource\Pages\ListStorageCredentials::class,
            'create' => \App\Filament\Resources\StorageCredentialResource\Pages\CreateStorageCredential::class,
            'edit' => \App\Filament\Resources\StorageCredentialResource\Pages\EditStorageCredential::class,
        ];
    }
}
