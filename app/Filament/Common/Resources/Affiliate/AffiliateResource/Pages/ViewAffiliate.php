<?php

namespace App\Filament\Common\Resources\Affiliate\AffiliateResource\Pages;

use App\Filament\Common\Resources\Affiliate\AffiliateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAffiliate extends ViewRecord
{
    protected static string $resource = AffiliateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
