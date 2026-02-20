<?php

namespace App\Filament\Resources\ApiSpaces\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ApiSpacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.title')
                    ->label('API Project')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Vendor/User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('website')
                    ->searchable(),
                TextColumn::make('environment')
                    ->badge(),
                TextColumn::make('requests_today')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('requests_this_month')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('status')
                    ->boolean(fn ($state) => $state === 'active')
                    ->label('Active'),
                TextColumn::make('last_request_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('environment')
                    ->options([
                        'prod' => 'Production',
                        'staging' => 'Staging',
                        'dev' => 'Development',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'paused' => 'Paused',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
