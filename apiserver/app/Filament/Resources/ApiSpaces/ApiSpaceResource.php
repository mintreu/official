<?php

namespace App\Filament\Resources\ApiSpaces;

use App\Filament\Resources\ApiSpaces\Pages\CreateApiSpace;
use App\Filament\Resources\ApiSpaces\Pages\EditApiSpace;
use App\Filament\Resources\ApiSpaces\Pages\ListApiSpaces;
use App\Filament\Resources\ApiSpaces\Pages\ViewApiSpace;
use App\Filament\Resources\ApiSpaces\Schemas\ApiSpaceForm;
use App\Filament\Resources\ApiSpaces\Schemas\ApiSpaceInfolist;
use App\Filament\Resources\ApiSpaces\Tables\ApiSpacesTable;
use App\Models\Api\ApiSpace;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ApiSpaceResource extends Resource
{
    protected static ?string $model = ApiSpace::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $navigationLabel = 'API Sites';

    protected static string|UnitEnum|null $navigationGroup = 'SaaS Operations';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ApiSpaceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApiSpaceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApiSpacesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApiSpaces::route('/'),
            'create' => CreateApiSpace::route('/create'),
            'view' => ViewApiSpace::route('/{record}'),
            'edit' => EditApiSpace::route('/{record}/edit'),
        ];
    }
}
