<?php

namespace App\Filament\Resources\GitAccountTokens\Pages;

use App\Filament\Resources\GitAccountTokens\GitAccountTokenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGitAccountToken extends EditRecord
{
    protected static string $resource = GitAccountTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // The token field is already encrypted via dehydrateStateUsing
        // Move it to encrypted_token field if provided
        if (isset($data['token']) && filled($data['token'])) {
            $data['encrypted_token'] = $data['token'];
        }
        unset($data['token']);

        return $data;
    }
}
