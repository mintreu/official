<?php

namespace App\Filament\Common\Schemas\Studio;

use App\Models\Enums\ProductTypeCast;
use App\Models\Product\Product;
use App\Models\Project\Project;
use App\Models\Subscription\Plan;
use App\Services\MoneyService\Money;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;


trait StudioFormSchema
{


    public function getStudioFormSchema():array
    {
        return [
            Wizard::make([

//                Wizard\Step::make('Product')
//                    ->icon('heroicon-m-square-3-stack-3d')
//                    ->schema([]),

                Wizard\Step::make('Studio')
                    ->icon('heroicon-c-squares-2x2')
                    ->schema(fn() => $this->prepareStudioForm()),



                Wizard\Step::make('Configuration')
                    ->columns()
                    ->icon('heroicon-m-cog')
                    ->schema(fn() => $this->prepareStudioConfigForm()),
                Wizard\Step::make('Subscribe')
                    ->icon('heroicon-s-shopping-cart')
                    ->schema(fn() => $this->getSubscriptionFormSchema()),
            ])
                ->columnSpanFull()
                ->skippable()
                ->submitAction(new HtmlString(Blade::render(<<<BLADE
                        <x-filament::button
                            type="submit"
                            size="sm"
                        >
                            Submit
                        </x-filament::button>
                    BLADE))),
        ];
    }




    // Sub Schemas

    public function prepareStudioForm()
    {
        return [

            Forms\Components\Section::make('About Studio')
                ->aside()
                ->description('Provide your studio information')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label('Studio Name')
                        ->prefixIcon('heroicon-c-squares-2x2')
                        ->columnSpanFull()
                        ->maxLength(255),
                ]),


            Forms\Components\Section::make('Product You Like')
                    ->description('Choose Product or Service For Studio')
                    ->aside()
                    ->schema([

                        Forms\Components\Select::make('product_id')
                            ->label('Choose Product')
                            ->inlineLabel()
                            ->placeholder('Select a product')
                            ->relationship('product','name')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} [{$record->type->getLabel()}]")
                            ->live()
                            ->required()
                            ->default(fn($state) => $state)
                            ->disabled(fn() => !is_null($this->product)),


                    ]),


            Forms\Components\Section::make('Plan & Subscription')
                ->visible(fn(Forms\Get $get) => $get('product_id'))
                ->aside()
                ->description('')
                ->schema([
                    Forms\Components\Select::make('plan_id')
                        ->label('Choose Plan')
                        ->inlineLabel()
                        ->placeholder('Select a plan')
                        ->relationship(
                            'plan',
                            'name',
                            fn (Forms\Get $get, $query) => $query
                                ? $query
                                    ->when($get('product_id'), fn ($q, $productId) =>
                                    $q->where('product_id', $productId)
                                        ->orderByDesc('price')
                                    )
                                : $query
                        )
                        ->disabled(!is_null($this->plan_id))
                        ->getOptionLabelFromRecordUsing(function (Model $record) {
                            $limitText = ($record->per_month_limit === 0 || $record->per_month_limit === null)
                                ? 'Unlimited'
                                : number_format($record->per_month_limit) . ' calls';

                            return "{$record->name} | Price: " . Money::format($record->price) . " | Monthly Cap: {$limitText}";
                        })
                        ->helperText('')
                        ->live()
                        ->required(),

                    Forms\Components\Select::make('duration_in_months')
                        ->label('Duration')
                        ->inlineLabel()
                        ->options([
                            1 => '1 Month',
                            3 => '3 Months',
                            6 => '6 Months',
                            12 => '12 Months',
                            36 => '36 Months',
                        ])
                        ->default(3)
                        ->required()



                ]),




        ];
    }



    protected function prepareStudioConfigForm(): array
    {
        return [
            Forms\Components\Section::make('Hosting Info')
                ->aside()
                ->schema([
                    Forms\Components\Select::make('domain_schema')
                        ->required()
                        ->label('Schema')
                        ->live()
                        ->options([
                            'https://' => 'https://',
                            'http://' => 'http://',
                        ])
                        ->helperText('set domain schema')
                        ->default('http://'),

                    Forms\Components\TextInput::make('domain')
                        ->required()
                        ->label('Set Domain')
                        ->columnSpan(3)
                        ->lazy()
                        ->prefix(fn(Forms\Get $get) => $get('domain_schema'))
                        ->helperText(fn(Forms\Get $get,$state) => $state ? 'Studio Url : '.$get('domain_schema').$state : 'type your domain name')
                        // ->afterStateUpdated(fn(Forms\Set $set,Forms\Get $get,$state) => $set('url',Hash::make($get('domain_schema').$state)))
                        ->maxLength(255),
                ])->columnSpanFull()->columns(4),




        ];
    }




    public function getSubscriptionFormSchema():array
    {
        return [

        ];
    }


}
