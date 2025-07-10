<?php

namespace App\Filament\Resources\PluginResource\Pages;

use App\Filament\Resources\PluginResource;
use App\Models\Enums\PluginStatusCast;
use App\Models\FakePlugin;
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

class ListPlugins extends ListRecords
{
    protected static string $resource = PluginResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

//    public function getTabs(): array
//    {
//        return [
//            'marketplace' => Tab::make('Marketplace')
//                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'marketplace')),
//            'installed' => Tab::make('Installed')
//                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'installed')),
//            'library' => Tab::make('Library')
//                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'available')),
//        ];
//    }



    public  function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vendor')
                    ->searchable(),
//                Tables\Columns\TextColumn::make('src_path')
//                    ->toggleable()
//                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('install_now')
                    ->label('Install Now')
                    ->requiresConfirmation()
                    ->visible(fn(Model $record) => $record->status == PluginStatusCast::MARKETPLACE)
                    ->action(function (Model $record){
                        $record->install();
                        Notification::make()->title('Installed')->body($record->name.' Successfully Installed in your system')->success()->send();
                    }),

                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->requiresConfirmation()
                    ->visible(fn(Model $record) => $record->status == PluginStatusCast::INSTALLED)
                    ->action(function (Model $record){
                        $record->activate();
                        Notification::make()->title('Installed')->body($record->name.' Successfully Installed in your system')->success()->send();
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }



}
