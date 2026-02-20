<?php

namespace App\Filament\Resources\ApiSpaces\Pages;

use App\Filament\Resources\ApiSpaces\ApiSpaceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewApiSpace extends ViewRecord
{
    protected static string $resource = ApiSpaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
