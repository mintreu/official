<?php

namespace App\Filament\Resources\CaseStudies\Schemas;

use App\Casts\PublishableStatusCast;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CaseStudyForm
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
                TextInput::make('client'),
                TextInput::make('industry'),
                TextInput::make('duration'),
                TextInput::make('technologies'),
                Textarea::make('challenge')
                    ->columnSpanFull(),
                Textarea::make('solution')
                    ->columnSpanFull(),
                TextInput::make('results'),
                Select::make('status')
                    ->options(PublishableStatusCast::class)
                    ->default('draft')
                    ->required(),
                Toggle::make('featured')
                    ->required(),
            ]);
    }
}
