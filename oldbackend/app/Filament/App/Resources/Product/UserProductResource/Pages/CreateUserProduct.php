<?php

namespace App\Filament\App\Resources\Product\UserProductResource\Pages;

use App\Filament\App\Resources\Product\UserProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserProduct extends CreateRecord
{
    protected static string $resource = UserProductResource::class;
}
