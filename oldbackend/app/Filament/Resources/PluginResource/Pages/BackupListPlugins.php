<?php

namespace App\Filament\Resources\PluginResource\Pages;

use App\Filament\Resources\PluginResource;
use App\Models\Plugin;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BackupListPlugins extends ListRecords
{
    protected static string $resource = PluginResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'marketplace' => Tab::make('Marketplace')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereRaw('1 = 0')), // Prevent real records from showing
            'installed' => Tab::make('Installed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'installed')),
            'library' => Tab::make('Library')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'available')),
        ];
    }

    public function getTableRecords(): Collection|Paginator|CursorPaginator
    {
        if ($this->activeTab === 'marketplace') {
            return Plugin::hydrate($this->getGitHubRepos());
        }

        return parent::getTableRecords();
    }

    private function getGitHubRepos(): array
    {
        return [
            [
                'id' => 9991,
                'name' => 'PayPal SDK',
                'slug' => 'paypal-sdk',
                'version' => '1.0.0',
                'src_path' => 'https://github.com/paypal/Checkout-PHP-SDK.git',
                'status' => 'not_installed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9992,
                'name' => 'DOMPDF',
                'slug' => 'dompdf',
                'version' => '3.1.0',
                'src_path' => 'https://github.com/dompdf/dompdf/releases/download/v3.1.0/dompdf-3.1.0.zip',
                'status' => 'not_installed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9993,
                'name' => 'PHPWord',
                'slug' => 'phpword',
                'version' => '1.4.0',
                'src_path' => 'https://github.com/PHPOffice/PHPWord/archive/refs/tags/1.4.0.zip',
                'status' => 'not_installed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'default' => 1,
                'md' => 2
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('name')->label('Name')->searchable(),
                    Tables\Columns\TextColumn::make('version')->label('Version'),
                    // Tables\Columns\TextColumn::make('src_path')->label('Source')->limit(30),
                    Tables\Columns\TextColumn::make('status')->label('Status'),
                ])->columnSpanFull(),
            ])
            ->actions([
//                Tables\Actions\ViewAction::make()
//                    ->visible(fn() => $this->activeTab !== 'marketplace'),
//                Tables\Actions\EditAction::make()
//                    ->visible(fn() => $this->activeTab !== 'marketplace'),
                Tables\Actions\Action::make('install')
                    ->label('Install')
                    ->visible(fn($record) => $this->activeTab === 'marketplace')
                    ->requiresConfirmation()
                    ->record(fn($record) => $record) // prevent re-resolution
                    ->action(fn($record) => $this->installPlugin($record)),

                Tables\Actions\Action::make('ewwe')
                    ->requiresConfirmation()
                    ->action(function (){
                        Notification::make()->title('asdfasf')->send();
                    })


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => $this->activeTab !== 'marketplace'),
                ]),
            ]);
    }

    public function installPlugin(Plugin $record)
    {
        dd($record);
        $pluginDirectory = app_path("Plugins/{$record->slug}");

        dd($pluginDirectory);

        if (!is_dir($pluginDirectory)) {
            mkdir($pluginDirectory, 0755, true);

            // Download and extract ZIP
            if (str_ends_with($record->src_path, '.zip')) {
                $zipPath = storage_path("app/{$record->slug}.zip");
                file_put_contents($zipPath, file_get_contents($record->src_path));

                $zip = new \ZipArchive;
                if ($zip->open($zipPath) === true) {
                    $zip->extractTo($pluginDirectory);
                    $zip->close();
                }

                unlink($zipPath);
            }

            // Clone from Git repo
            elseif (str_ends_with($record->src_path, '.git')) {
                shell_exec("git clone {$record->src_path} {$pluginDirectory}");
            }

            // Save to DB
            Plugin::firstOrCreate([
                'slug' => $record->slug,
            ], [
                'name' => $record->name,
                'version' => $record->version,
                'src_path' => $record->src_path,
                'status' => 'installed',
            ]);
        }
    }
}
