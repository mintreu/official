<?php

namespace App\Filament\Common\Schemas\Studio;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;

trait StudioInfolistSchema
{



    public function getStudioInfolistSchema():array
    {
        return [

            Section::make('Studio Details')
                ->aside()
                ->schema([

                    TextEntry::make('name')
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::SemiBold)
                        ->color('primary'),

                    TextEntry::make('domain_schema'),
                    TextEntry::make('domain'),

                    TextEntry::make('expire_on')->since(),
                ]),

            Section::make('Plan Details')
                ->aside()
                ->schema([
                    TextEntry::make('plan.name'),
                ]),

            Section::make('Product Details')
                ->aside()
                ->schema([
                    TextEntry::make('product.name'),
                ]),

            Section::make('Subscription Details')
                ->aside()
                ->schema([
                    TextEntry::make('user.name'),
                ]),


        ];
    }





}
