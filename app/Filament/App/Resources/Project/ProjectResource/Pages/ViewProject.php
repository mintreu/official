<?php

namespace App\Filament\App\Resources\Project\ProjectResource\Pages;

use App\Filament\App\Resources\Product\ProductResource;
use App\Filament\App\Resources\Project\ProjectResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }


    public function infolist(Infolist $infolist): Infolist
    {
        return parent::infolist($infolist)
            ->schema([

                Split::make([

                    Grid::make(1)
                        ->grow()
                        ->schema([
                            TextEntry::make('name')->columnSpanFull(),
                            TextEntry::make('short_desc')->alignJustify()->columnSpanFull(),
                        ])
                        ->columnSpanFull(),

                    SpatieMediaLibraryImageEntry::make('displayImage')
                        ->hiddenLabel()
                        ->collection('displayImage')
                        ->extraImgAttributes(['class' => 'mx-auto rounded-xl shadow-lg w-full'])
                        ->size('70%'),


                ])->columnSpanFull(),

                TextEntry::make('desc')->alignJustify()->columnSpanFull(),


                RepeatableEntry::make('products')
                    ->label('All '.$this->record->name.' Products :-')
                    ->grid(3)
                    ->columnSpanFull()
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('displayImage')
                            ->collection('displayImage')
                            ->extraImgAttributes(['class' => 'mx-auto rounded-xl shadow-lg'])
                            ->size('50%'),
                        TextEntry::make('name'),

                        TextEntry::make('short_desc')->hiddenLabel()->columnSpanFull()->alignJustify(),

                        \Filament\Infolists\Components\Actions::make([

                            \Filament\Infolists\Components\Actions\Action::make('view')
                                ->label('View Product')
                                ->url(fn(Model $record) => ProductResource::getUrl('view',['record' => $record->url]))
                        ])->alignCenter()->columnSpanFull(),



                    ])

            ]);
    }


}
