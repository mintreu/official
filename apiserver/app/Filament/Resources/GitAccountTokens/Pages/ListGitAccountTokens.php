<?php

namespace App\Filament\Resources\GitAccountTokens\Pages;

use App\Filament\Resources\GitAccountTokens\GitAccountTokenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGitAccountTokens extends ListRecords
{
    protected static string $resource = GitAccountTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
