<?php

namespace App\Filament\Resources\GitAccountTokens\Pages;

use App\Filament\Resources\GitAccountTokens\GitAccountTokenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGitAccountToken extends CreateRecord
{
    protected static string $resource = GitAccountTokenResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // The token field is already encrypted via dehydrateStateUsing
        // Move it to encrypted_token field
        if (isset($data['token'])) {
            $data['encrypted_token'] = $data['token'];
            unset($data['token']);
        }

        return $data;
    }
}
