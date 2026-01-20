<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Casts\PublishableStatusCast;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('category'),
                TextInput::make('technologies'),
                Select::make('status')
                    ->options(PublishableStatusCast::class)
                    ->default('draft')
                    ->required(),
                Toggle::make('featured')
                    ->required(),
                TextInput::make('live_url'),
                TextInput::make('github_url'),
            ]);
    }
}
