<?php

namespace App\Filament\Resources\Subscription;

use App\Filament\Resources\Subscription\PlanResource\Pages;
use App\Filament\Resources\Subscription\PlanResource\RelationManagers;
use App\Models\Subscription\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $activeNavigationIcon = 'heroicon-s-rectangle-stack';
    protected static ?string $navigationGroup = 'Product Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General')
                    ->aside()
                    ->columns()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->lazy()
                            ->afterStateUpdated(fn(Forms\Set $set,$state) => $set('url',Str::slug($state)))
                            ->required(),
                        Forms\Components\TextInput::make('url')
                            ->unique()
                            ->required(),
                        Forms\Components\Textarea::make('desc')
                            ->rows(8)
                            ->columnSpanFull(),
                    ]),


                Forms\Components\Section::make('Pricing')
                    ->aside()
                    ->columns()
                    ->schema([
                        Forms\Components\TextInput::make('base_price')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('hsn_code'),
                        Forms\Components\TextInput::make('tax_percent')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('tax_amount')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('$'),
                    ]),


              Forms\Components\Section::make('Manage')
                ->aside()
                  ->columns()
                ->schema([
                    Forms\Components\TextInput::make('per_month_limit')
                        ->numeric(),
                    Forms\Components\TextInput::make('auth_type'),
                    Forms\Components\TextInput::make('support_type'),
                    Forms\Components\TextInput::make('documentation_type'),
                    Forms\Components\Toggle::make('is_recommended')
                        ->required(),
                    Forms\Components\Toggle::make('is_enterprise')
                        ->required(),

                    Forms\Components\Toggle::make('visible_on_front')
                        ->required(),
                ]),

                Forms\Components\Section::make('Features')
                    ->aside()
                    ->schema([
                        Forms\Components\KeyValue::make('features')
                            ->addActionLabel('Add property')
                            ->keyLabel('Property name')
                            ->keyPlaceholder('Property name')
                            ->valueLabel('Property value')
                            ->valuePlaceholder('Property value')
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('per_month_limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('auth_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('support_type')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_recommended')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'view' => Pages\ViewPlan::route('/{record}'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
