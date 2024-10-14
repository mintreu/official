<?php

namespace App\Filament\Resources\Product\ProductResource\Pages;

use App\Filament\Resources\Product\ProductResource;
use App\Services\MoneyService\Money;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Table;
use Filament\Tables;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }



    public  function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'lg' => 3
            ])
            ->columns([

                Tables\Columns\Layout\Stack::make([

                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('name')
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->color('primary')
                            ->searchable(),

                        Tables\Columns\TextColumn::make('type')
                            ->alignRight()
                            ->badge(),
                    ])->columnSpanFull(),

                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('price')
                            ->money(Money::defaultCurrency())
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                            ->weight(FontWeight::SemiBold)
                            ->sortable(),

                        Tables\Columns\TextColumn::make('category.name')
                            ->default('--none--')
                            ->alignRight()
                            ->badge(),
                    ])->columnSpanFull(),


                    Tables\Columns\Layout\Split::make([

                        Tables\Columns\TextColumn::make('project.name')
                            ->badge(),

                        Tables\Columns\IconColumn::make('status')
                            ->alignRight()
                            ->boolean(),

                    ])->columnSpanFull(),

                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('created_at')
                            ->dateTime()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                        Tables\Columns\TextColumn::make('updated_at')
                            ->dateTime()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                    ]),



                ]),





//                Tables\Columns\IconColumn::make('chargeable')
//                    ->boolean(),






            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }








}
