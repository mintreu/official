<?php

namespace App\Filament\Resources\ApiSpaces\Pages;

use App\Filament\Resources\ApiSpaces\ApiSpaceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditApiSpace extends EditRecord
{
    protected static string $resource = ApiSpaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
