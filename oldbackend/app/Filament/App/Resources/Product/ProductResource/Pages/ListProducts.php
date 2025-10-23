<?php

namespace App\Filament\App\Resources\Product\ProductResource\Pages;

use App\Filament\App\Resources\Product\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }


    public  function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
            ])
            ->columns([

                Tables\Columns\Layout\Split::make([


                    Tables\Columns\SpatieMediaLibraryImageColumn::make('displayImage')
                        ->size('80%')
                        ->collection('displayImage'),


                    Tables\Columns\Layout\Stack::make([

                        Tables\Columns\TextColumn::make('name')
                            ->weight(FontWeight::SemiBold)
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                            ->color('primary')
                            ->searchable(),

                        Tables\Columns\TextColumn::make('type')
                            ->color('warning')
                            ->badge(),

                        Tables\Columns\TextColumn::make('project.name')
                            ->color('info')
                            ->badge(),


                        Tables\Columns\TextColumn::make('updated_at')
                            ->dateTime()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                    ])->extraAttributes(['class' => 'gap-2']),


                ])->columnSpanFull(),




            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([

            ]);
    }






}
