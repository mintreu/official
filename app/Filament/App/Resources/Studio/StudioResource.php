<?php

namespace App\Filament\App\Resources\Studio;

use App\Filament\App\Resources\Studio\StudioResource\Pages;
use App\Filament\App\Resources\Studio\StudioResource\RelationManagers;
use App\Models\Studio\Studio;

use Filament\Resources\Resource;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudioResource extends Resource
{
    protected static ?string $model = Studio::class;

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
            'index' => Pages\ListStudios::route('/'),
            'create' => Pages\CreateStudio::route('/create'),
            'view' => Pages\ViewStudio::route('/{record}'),
            'edit' => Pages\EditStudio::route('/{record}/edit'),
        ];
    }
}
