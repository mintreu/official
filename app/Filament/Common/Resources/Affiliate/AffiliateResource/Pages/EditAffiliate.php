<?php

namespace App\Filament\Common\Resources\Affiliate\AffiliateResource\Pages;

use App\Filament\Common\Resources\Affiliate\AffiliateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAffiliate extends EditRecord
{
    protected static string $resource = AffiliateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
