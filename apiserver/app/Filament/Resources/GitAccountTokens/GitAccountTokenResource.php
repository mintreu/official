<?php

namespace App\Filament\Resources\GitAccountTokens;

use App\Filament\Resources\GitAccountTokens\Pages\CreateGitAccountToken;
use App\Filament\Resources\GitAccountTokens\Pages\EditGitAccountToken;
use App\Filament\Resources\GitAccountTokens\Pages\ListGitAccountTokens;
use App\Models\GitAccountToken;
use BackedEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Crypt;

class GitAccountTokenResource extends Resource
{
    protected static ?string $model = GitAccountToken::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $navigationLabel = 'Git Tokens';

    protected static ?int $navigationSort = 100;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Mintreu Organization'),

                Select::make('provider')
                    ->options([
                        'github' => 'GitHub',
                        'gitlab' => 'GitLab',
                        'bitbucket' => 'Bitbucket',
                    ])
                    ->required(),

                TextInput::make('account_identifier')
                    ->label('Account/Org Name')
                    ->maxLength(255)
                    ->placeholder('e.g., mintreu'),

                TextInput::make('token')
                    ->label('Personal Access Token')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrateStateUsing(fn (?string $state) => $state ? Crypt::encryptString($state) : null)
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->helperText('Token is encrypted before storage. Leave empty to keep existing token.'),

                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),

                DateTimePicker::make('expires_at')
                    ->label('Token Expiration'),

                Checkbox::make('is_active')
                    ->label('Active')
                    ->default(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('provider')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'github' => 'gray',
                        'gitlab' => 'warning',
                        'bitbucket' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('account_identifier')
                    ->label('Account')
                    ->searchable(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable()
                    ->color(fn (?string $state) => $state && now()->gt($state) ? 'danger' : null),

                TextColumn::make('last_used_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Never'),

                TextColumn::make('last_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Never'),

                TextColumn::make('productSources_count')
                    ->counts('productSources')
                    ->label('Sources'),
            ])
            ->filters([
                SelectFilter::make('provider')
                    ->options([
                        'github' => 'GitHub',
                        'gitlab' => 'GitLab',
                        'bitbucket' => 'Bitbucket',
                    ]),
                SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->actions([
                Action::make('verify')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (GitAccountToken $record) {
                        if ($record->verify()) {
                            Notification::make()
                                ->success()
                                ->title('Token verified successfully')
                                ->send();
                        } else {
                            Notification::make()
                                ->danger()
                                ->title('Token verification failed')
                                ->body('The token may be expired or revoked')
                                ->send();
                        }
                    }),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGitAccountTokens::route('/'),
            'create' => CreateGitAccountToken::route('/create'),
            'edit' => EditGitAccountToken::route('/{record}/edit'),
        ];
    }
}
