<?php

namespace App\Filament\App\Resources\Project\ProjectResource\Pages;

use App\Filament\App\Resources\Project\ProjectResource;
use Filament\Actions;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

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
                'lg' => 3
            ])
            ->columns([

               Tables\Columns\Layout\Stack::make([
                   Tables\Columns\SpatieMediaLibraryImageColumn::make('displayImage')
                       ->size('80%')
                       ->alignCenter()
                       ->extraImgAttributes(['class' => 'mx-auto rounded-xl shadow-lg'])
                       ->collection('displayImage'),

                   Tables\Columns\TextColumn::make('name')
                       ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                       ->weight(FontWeight::SemiBold)
                       ->color('success')
                       ->alignCenter()
                       ->extraAttributes(['class' => 'my-2'])
                       ->searchable(),
               ])->columnSpanFull()

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
