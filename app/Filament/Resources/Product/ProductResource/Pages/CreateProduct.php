<?php

namespace App\Filament\Resources\Product\ProductResource\Pages;

use App\Filament\Resources\Product\ProductResource;
use App\Models\Enums\Product\ProductTypeCast;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;





    public  function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Information')
                    ->aside()
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->lazy()
                            ->required()
                            ->afterStateUpdated(function ($state,Set $set){
                                $set('url',Str::slug($state));
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
                    ])




            ]);
    }



}
