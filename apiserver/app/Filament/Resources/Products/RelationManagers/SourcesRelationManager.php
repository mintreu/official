<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Enums\SourceProvider;
use App\Models\GitAccountToken;
use App\Models\Products\ProductSource;
use App\Services\GitReleaseService;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'sources';

    protected static ?string $title = 'Download Sources';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('provider')
                    ->options(SourceProvider::class)
                    ->required()
                    ->live()
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Windows x64, Linux ARM, Main Release'),

                TextInput::make('version')
                    ->maxLength(50)
                    ->placeholder('e.g., v1.0.0'),

                Textarea::make('source_url')
                    ->required()
                    ->rows(2)
                    ->placeholder('https://github.com/owner/repo/archive/refs/tags/v1.zip')
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->rows(2)
                    ->columnSpanFull(),

                Select::make('git_account_token_id')
                    ->label('Git Account Token')
                    ->options(fn () => GitAccountToken::query()->where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('Use config token (fallback)')
                    ->helperText('Select a stored token for private repos, or leave empty to use .env config'),

                TextInput::make('file_name')
                    ->maxLength(255)
                    ->placeholder('repo-v1.0.0.zip'),

                TextInput::make('file_size')
                    ->numeric()
                    ->suffix('bytes')
                    ->placeholder('File size in bytes'),

                KeyValue::make('metadata')
                    ->label('Provider Metadata')
                    ->keyLabel('Key')
                    ->valueLabel('Value')
                    ->addActionLabel('Add metadata')
                    ->columnSpanFull(),

                Checkbox::make('is_primary')
                    ->label('Primary source (shown first)')
                    ->default(false),

                Checkbox::make('is_active')
                    ->label('Active (available for download)')
                    ->default(true),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('provider')
                    ->badge()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('version')
                    ->badge()
                    ->color('info'),

                TextColumn::make('file_size')
                    ->formatStateUsing(function (?int $state, ProductSource $record) {
                        return $record->getFileSizeFormatted() ?? '-';
                    })
                    ->label('Size'),

                IconColumn::make('is_primary')
                    ->boolean()
                    ->label('Primary'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                TextColumn::make('gitAccountToken.name')
                    ->label('Token')
                    ->placeholder('Config')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('last_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Verified')
                    ->placeholder('Never'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                Action::make('fetch_from_repo')
                    ->label('Fetch from Repo')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        TextInput::make('repo_url')
                            ->label('Repository URL')
                            ->required()
                            ->placeholder('https://github.com/owner/repo'),
                        Select::make('token_id')
                            ->label('Use Token')
                            ->options(fn () => GitAccountToken::query()->where('is_active', true)->pluck('name', 'id'))
                            ->placeholder('Config token'),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        $service = app(GitReleaseService::class);
                        $parsed = $service->parseRepoUrl($data['repo_url']);

                        if (! $parsed) {
                            Notification::make()
                                ->danger()
                                ->title('Invalid repository URL')
                                ->send();

                            return;
                        }

                        $token = $data['token_id']
                            ? GitAccountToken::find($data['token_id'])?->getToken()
                            : config('services.github.token');

                        $repoInfo = $service->getGitHubRepoInfo($parsed['owner'], $parsed['repo'], $token);
                        $latestRelease = $service->getLatestGitHubRelease($parsed['owner'], $parsed['repo'], $token);

                        if (! $repoInfo) {
                            Notification::make()
                                ->danger()
                                ->title('Could not fetch repository info')
                                ->body('Check if the repo exists and token has access')
                                ->send();

                            return;
                        }

                        $downloadUrl = $service->getGitHubDownloadUrl(
                            $parsed['owner'],
                            $parsed['repo'],
                            $latestRelease['tag_name'] ?? $repoInfo['default_branch'],
                            null,
                            $token
                        );

                        $livewire->getOwnerRecord()->sources()->create([
                            'provider' => $parsed['provider'],
                            'name' => $repoInfo['name'].' Release',
                            'source_url' => $downloadUrl,
                            'version' => $latestRelease['tag_name'] ?? null,
                            'file_name' => $parsed['repo'].'-'.($latestRelease['tag_name'] ?? 'main').'.zip',
                            'metadata' => [
                                'owner' => $parsed['owner'],
                                'repo' => $parsed['repo'],
                                'tag' => $latestRelease['tag_name'] ?? null,
                                'is_private' => $repoInfo['is_private'],
                            ],
                            'git_account_token_id' => $data['token_id'],
                            'is_primary' => $livewire->getOwnerRecord()->sources()->count() === 0,
                            'is_active' => true,
                            'last_verified_at' => now(),
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Source created from repository')
                            ->body("Added {$repoInfo['name']} - ".($latestRelease['tag_name'] ?? 'latest'))
                            ->send();
                    }),
            ])
            ->actions([
                Action::make('verify')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (ProductSource $record) {
                        $token = $record->getEffectiveToken();
                        $service = app(GitReleaseService::class);

                        $metadata = $record->metadata ?? [];
                        $owner = $metadata['owner'] ?? null;
                        $repo = $metadata['repo'] ?? null;

                        if ($owner && $repo) {
                            $hasAccess = $service->checkRepoAccess('github', $owner, $repo, $token);

                            if ($hasAccess) {
                                $record->update(['last_verified_at' => now()]);
                                Notification::make()->success()->title('Source verified')->send();
                            } else {
                                Notification::make()->danger()->title('Verification failed')->body('Cannot access repository')->send();
                            }
                        } else {
                            Notification::make()->warning()->title('Cannot verify')->body('Missing owner/repo metadata')->send();
                        }
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('is_primary', 'desc');
    }
}
