<?php

namespace App\Filament\Resources\Licenses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LicenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'title')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                Select::make('plan_id')
                    ->relationship('plan', 'name')
                    ->placeholder('No plan'),
                TextInput::make('license_key')
                    ->required(),
                Select::make('type')
                    ->options([
                        'api_subscription' => 'API Subscription',
                        'commercial_single_use' => 'Commercial Single',
                        'commercial_3_uses' => 'Commercial 3 Uses',
                        'commercial_10_uses' => 'Commercial 10 Uses',
                        'free_single_use' => 'Free Single Use',
                        'free_unlimited' => 'Free Unlimited',
                        'free_attribution' => 'Free Attribution',
                        'demo' => 'Demo',
                    ])
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('usage_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('max_usage')
                    ->numeric(),
                KeyValue::make('meta')
                    ->columnSpanFull(),
                KeyValue::make('server_info')
                    ->columnSpanFull(),
                DateTimePicker::make('expires_at'),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('first_used_at'),
                DateTimePicker::make('last_used_at'),
            ]);
    }
}
