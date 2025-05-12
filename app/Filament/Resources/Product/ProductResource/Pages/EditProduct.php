<?php

namespace App\Filament\Resources\Product\ProductResource\Pages;

use App\Filament\Resources\Product\ProductResource;
use App\Models\Enums\Product\ProductTypeCast;
use Filament\Actions;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Set;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([


                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->schema([


                                Forms\Components\Section::make('Information')
                                    ->aside()
                                    ->schema([

                                        Forms\Components\TextInput::make('name')
                                            ->lazy()
                                            ->required()
                                            ->afterStateUpdated(function ($state, Set $set) {
                                                $set('url', Str::slug($state));
                                            })
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('url')
                                            ->required()
                                            ->maxLength(255),
                                    ]),


                                Forms\Components\Section::make('Setting')
                                    ->aside()
                                    ->columns(3)
                                    ->schema([

                                        Forms\Components\Toggle::make('status')
                                            ->required(),

                                        Forms\Components\Toggle::make('featured')
                                            ->required(),
                                        Forms\Components\Toggle::make('visibility')
                                            ->required(),

                                    ]),


                                Forms\Components\Section::make('Manage')
                                    ->aside()
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\Select::make('type')
                                            ->options(fn() => collect(ProductTypeCast::cases())->mapWithKeys(function ($case) {
                                                return [$case->value => $case->name];
                                            })->toArray()),

                                        Forms\Components\Select::make('service_id')
                                            ->relationship('services', 'name'),
                                        Forms\Components\Select::make('category_id')
                                            ->relationship('category', 'name'),

                                        Forms\Components\Select::make('project_id')
                                            ->relationship('project', 'name'),
                                    ]),


                            ]),
                        Tabs\Tab::make('Media')
                            ->schema([


                            ]),
                        Tabs\Tab::make('Detail')
                            ->schema([
                                Forms\Components\Section::make('Description')
                                    ->aside()
                                    ->relationship('flat')
                                    ->schema([

                                        Forms\Components\Textarea::make('short_desc'),
                                        Forms\Components\RichEditor::make('desc')

                                    ])
                            ]),
                        Tabs\Tab::make('Server')
                            ->schema([

                                Forms\Components\Section::make('Server Info')
                                    ->aside()
                                    ->schema([
                                        Forms\Components\TextInput::make('host_url')->prefix('https://'),
                                        Forms\Components\TextInput::make('host_api_url')->prefix('https://'),
                                        Forms\Components\TextInput::make('client_login_url')->prefix('https://'),
                                    ]),

                                Forms\Components\Repeater::make('demo_accounts')
                                    ->label('Demo Accounts')
                                    ->relationship('flat')
                                    ->defaultItems(2)
                                    ->maxItems(3)
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('email')->email()->required(),
                                        Forms\Components\TextInput::make('password')->password()->revealable()->required(),
                                        Forms\Components\Select::make('type')
                                            ->required()
                                            ->placeholder('Select a user type')
                                            ->options([
                                                'admin' => 'Admin',
                                                'user' => 'EndUser (User/Customer)',
                                                'vendor' => 'Vendor'
                                            ])


                                    ])


                            ]),
                        Tabs\Tab::make('Plans')
                            ->schema([

                                Forms\Components\Repeater::make('plans')
                                    ->relationship('plans')
                                    ->columns(3)
                                    ->schema([

                                        Forms\Components\TextInput::make('name')->required()->columnSpanFull(),
                                        Forms\Components\Textarea::make('desc')->required()->columnSpanFull(),
                                        Forms\Components\TextInput::make('price')->numeric()->required(),
                                        Forms\Components\TextInput::make('per_month_limit')->numeric()->required()->default(0)->hint('0 means unlimited'),
                                        Forms\Components\TextInput::make('records_limit')->numeric()->required()->default(0)->hint('0 means unlimited'),

                                        Forms\Components\Toggle::make('is_recommended')->default(false)->required(),
                                        Forms\Components\Toggle::make('is_enterprise')->default(false)->required(),
                                        Forms\Components\Toggle::make('visible_on_front')->default(false)->required(),
                                        Forms\Components\Toggle::make('has_support')->default(false)->required(),

                                    ])

                            ])
                    ]),


            ]);
    }


}
