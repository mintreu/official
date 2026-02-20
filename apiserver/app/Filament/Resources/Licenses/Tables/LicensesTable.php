<?php

namespace App\Filament\Resources\Licenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class LicensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.title')
                    ->label('Product')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('license_key')
                    ->searchable(),
                BadgeColumn::make('type')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('usage_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_usage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('first_used_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_used_at')
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
                SelectFilter::make('type')
                    ->options([
                        'api_subscription' => 'API Subscription',
                        'commercial_single_use' => 'Commercial Single',
                        'commercial_3_uses' => 'Commercial 3 Uses',
                        'commercial_10_uses' => 'Commercial 10 Uses',
                        'free_single_use' => 'Free Single Use',
                        'free_unlimited' => 'Free Unlimited',
                        'free_attribution' => 'Free Attribution',
                        'demo' => 'Demo',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Active'),
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
