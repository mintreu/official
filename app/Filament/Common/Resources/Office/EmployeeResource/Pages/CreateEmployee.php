<?php

namespace App\Filament\Common\Resources\Office\EmployeeResource\Pages;

use App\Filament\Common\Resources\Office\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
