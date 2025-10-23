<?php

namespace App\Filament\Resources\Form\ContactFormResource\Pages;

use App\Filament\Resources\Form\ContactFormResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactForms extends ListRecords
{
    protected static string $resource = ContactFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
