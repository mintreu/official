<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Casts\PublishableStatusCast;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('excerpt')
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('category'),
                TextInput::make('tags'),
                TextInput::make('author'),
                TextInput::make('reading_time')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->options(PublishableStatusCast::class)
                    ->default('draft')
                    ->required(),
                Toggle::make('featured')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
