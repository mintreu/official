<?php

namespace App\Filament\App\Resources\Studio\StudioResource\Pages;

use App\Filament\App\Resources\Studio\StudioResource;
use App\Filament\Common\Schemas\Studio\StudioInfolistSchema;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewStudio extends ViewRecord
{
    use StudioInfolistSchema;

    protected static string $resource = StudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }


    public function infolist(Infolist $infolist): Infolist
    {
        return parent::infolist($infolist)
            ->schema($this->getStudioInfolistSchema());
    }


}
