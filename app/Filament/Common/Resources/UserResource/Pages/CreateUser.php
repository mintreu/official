<?php

namespace App\Filament\Common\Resources\UserResource\Pages;

use App\Filament\Common\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
