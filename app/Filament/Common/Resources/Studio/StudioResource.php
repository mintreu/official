<?php

namespace App\Filament\Common\Resources\Studio;

use App\Filament\Common\Resources\StudioResource\Pages;
use App\Filament\Common\Resources\StudioResource\RelationManagers;
use App\Models\Studio\Studio;
use Filament\Resources\Resource;

class StudioResource extends Resource
{
    protected static ?string $model = Studio::class;
    protected static ?string $slug = 'studios';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';





    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Common\Resources\Studio\StudioResource\Pages\ListStudios::route('/'),
            'create' => \App\Filament\Common\Resources\Studio\StudioResource\Pages\CreateStudio::route('/create'),
            'view' => \App\Filament\Common\Resources\Studio\StudioResource\Pages\ViewStudio::route('/{record}'),
            'edit' => \App\Filament\Common\Resources\Studio\StudioResource\Pages\EditStudio::route('/{record}/edit'),
        ];
    }
}
