<?php

namespace App\Filament\App\Resources\Product\UserProductResource\Pages;

use App\Filament\App\Resources\Product\UserProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserProducts extends ListRecords
{
    protected static string $resource = UserProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
