<?php

namespace App\Filament\App\Resources\Product\ProductResource\Pages;

use App\Filament\App\Resources\Product\ProductResource;
use Filament\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }


    public function infolist(Infolist $infolist): Infolist
    {
        return parent::infolist($infolist)
            ->schema([

                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->contained(false)
                    ->tabs([
                        Tabs\Tab::make('Product')
                            ->schema([

                                Section::make('Product Information')
                                    ->aside()
                                    ->columns()
                                    ->schema([

                                        Grid::make(1)
                                            ->grow()
                                            ->columnSpan(1)
                                            ->schema([
                                                TextEntry::make('name')
                                                    ->label('Name : ')
                                                    ->size(TextEntry\TextEntrySize::Large)
                                                    ->weight(FontWeight::SemiBold)
                                                    ->inlineLabel()
                                                    ->grow(),

                                                TextEntry::make('project.name')
                                                    ->label('Project :')
                                                    ->inlineLabel()
                                                    ->columnSpanFull()
                                                    ->alignJustify(),

                                                TextEntry::make('short_desc')
                                                    ->label('About')
                                                    ->columnSpanFull()
                                                    ->alignJustify(),

                                            ]),

                                        SpatieMediaLibraryImageEntry::make('displayImage')
                                            ->hiddenLabel()
                                            ->alignCenter()
                                            ->size('70%')
                                            ->collection('displayImage'),

                                    ]),


                                Section::make('Description')
                                    ->aside()
                                    ->schema([
                                        TextEntry::make('desc')->hiddenLabel()->alignJustify()->columnSpanFull()
                                    ]),



                            ]),
                        Tabs\Tab::make('Previews')
                            ->schema([

                                SpatieMediaLibraryImageEntry::make('gallery')
                                    ->hiddenLabel()
                                    ->columns(4)
                                    ->square()
                                    ->collection('galleryImage')


                            ]),
                        Tabs\Tab::make('Demo')
                            ->schema([

                                TextEntry::make('heading')->hiddenLabel()
                                    ->default('You can view this service as demo'),


                               Fieldset::make('Admin Login')
                                   ->columns(1)
                                    ->schema([
                                        TextEntry::make('Email')
                                            ->size(TextEntry\TextEntrySize::Medium)
                                            ->inlineLabel()
                                            ->default('admin@example.com'),

                                        TextEntry::make('Password')
                                            ->size(TextEntry\TextEntrySize::Medium)
                                            ->inlineLabel()
                                            ->default('password'),


                                        \Filament\Infolists\Components\Actions::make([
                                            Action::make('visit')
                                                ->icon('heroicon-s-globe-asia-australia')
                                                ->badge()
                                                ->url('#')
                                        ])->alignLeft(),

                                    ]),

                                Fieldset::make('User Login')
                                    ->columns(1)
                                    ->schema([
                                        TextEntry::make('Email')
                                            ->size(TextEntry\TextEntrySize::Medium)
                                            ->inlineLabel()
                                            ->default('user@example.com'),

                                        TextEntry::make('Password')
                                            ->size(TextEntry\TextEntrySize::Medium)
                                            ->inlineLabel()
                                            ->default('password'),

                                        \Filament\Infolists\Components\Actions::make([
                                            Action::make('visit')
                                                ->icon('heroicon-s-globe-asia-australia')
                                                ->badge()
                                                ->url('#')
                                        ])->alignLeft(),
                                    ]),




                                // ...
                            ]),
                    ]),









            ]);
    }


}
