<?php

namespace App\Filament\Resources\ApiSpaces\Pages;

use App\Filament\Resources\ApiSpaces\ApiSpaceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApiSpaces extends ListRecords
{
    protected static string $resource = ApiSpaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
