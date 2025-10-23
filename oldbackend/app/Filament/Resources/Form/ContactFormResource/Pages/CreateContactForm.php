<?php

namespace App\Filament\Resources\Form\ContactFormResource\Pages;

use App\Filament\Resources\Form\ContactFormResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContactForm extends CreateRecord
{
    protected static string $resource = ContactFormResource::class;
}
