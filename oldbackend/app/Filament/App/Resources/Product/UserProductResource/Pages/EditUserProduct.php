<?php

namespace App\Filament\App\Resources\Product\UserProductResource\Pages;

use App\Filament\App\Resources\Product\UserProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserProduct extends EditRecord
{
    protected static string $resource = UserProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
