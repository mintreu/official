<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('project_type'),
                TextInput::make('budget'),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'new' => 'New',
                        'in_progress' => 'In progress',
                        'replied' => 'Replied',
                        'archived' => 'Archived',
                    ])
                    ->default('new')
                    ->required(),
                DateTimePicker::make('replied_at'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
