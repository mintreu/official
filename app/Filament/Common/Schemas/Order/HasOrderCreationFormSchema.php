<?php

namespace App\Filament\Common\Schemas\Order;

use App\Livewire\Filament\OrderPreviewTable;
use App\Models\Enums\Product\ProductTypeCast;
use App\Models\Product\Product;
use App\Models\Project\Project;
use App\Models\Subscription\Plan;
use App\Services\MoneyService\Money;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Icetalker\FilamentPicker\Forms\Components\Picker;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;

trait HasOrderCreationFormSchema
{



    public function getOrderCreationFormSchema():array
    {


        return [






            RadioDeck::make('name')
                ->columnSpanFull()
                ->options(fn() => $this->projects->pluck('name','id'))
                ->descriptions(fn() => $this->projects->pluck('short_desc','id'))
                ->icons([
                    'ios' => 'heroicon-m-device-phone-mobile',
                    'android' => 'heroicon-m-device-phone-mobile',
                    'web' => 'heroicon-m-globe-alt',
                    'windows' => 'heroicon-m-computer-desktop',
                    'mac' => 'heroicon-m-computer-desktop',
                    'linux' => 'heroicon-m-computer-desktop',
                ])
                ->required()
                ->iconSize(IconSize::Large) // Small | Medium | Large | (string - sm | md | lg)
                ->iconSizes([ // Customize the values for each icon size
                    'sm' => 'h-12 w-12',
                    'md' => 'h-14 w-14',
                    'lg' => 'h-16 w-16',
                ])
                ->iconPosition(IconPosition::Before) // Before | After | (string - before | after)
                ->alignment(Alignment::Center) // Start | Center | End | (string - start | center | end)
                ->gap('gap-5') // Gap between Icon and Description (Any TailwindCSS gap-* utility)
                ->padding('px-4 px-6') // Padding around the deck (Any TailwindCSS padding utility)
                ->direction('column') // Column | Row (Allows to place the Icon on top)
                ->extraCardsAttributes([ // Extra Attributes to add to the card HTML element
                    'class' => 'rounded-xl'
                ])
                ->extraOptionsAttributes([ // Extra Attributes to add to the option HTML element
                    'class' => 'text-3xl leading-none w-full flex flex-col items-center justify-center p-4'
                ])
                ->extraDescriptionsAttributes([ // Extra Attributes to add to the description HTML element
                    'class' => 'text-sm font-light text-center'
                ])
                ->color('primary') // supports all color custom or not
                ->multiple() // Select multiple card (it will also returns an array of selected card values)
                ->columns(3),



























        ];



    }







    public function getBaseTypeSchema()
    {
        $options = [
            'full-suite' => 'Full Suite',
            'combo-suite' => 'Combo Suite',
            'template' => 'Template',
            'mobile-application' => 'Mobile Application',
            'api' => 'API Access',
            'widget' => 'Widget'
        ];

        $descriptions = [
            'full-suite' => 'Access to the complete suite of tools and services.',
            'combo-suite' => 'A combination of essential tools and services tailored for your needs.',
            'template' => 'Pre-designed templates to jumpstart your project.',
            'mobile-application' => 'A fully functional mobile application for your business.',
            'api' => 'Secure and scalable API access for integration with your systems.',
            'widget' => 'Customizable widgets to enhance your web presence.'
        ];

        $icons = [
            'full-suite' => 'heroicon-s-sparkles',
            'combo-suite' => 'heroicon-m-globe-alt',
            'template' => 'heroicon-c-rectangle-stack',
            'mobile-application' => 'heroicon-m-device-phone-mobile',
            'api' => 'heroicon-m-link',
            'widget' => 'heroicon-m-puzzle-piece'
        ];




        return [
            $this->getDeck('base_type',$options,$descriptions,$icons)
                ->label(function($state) {
                    return $state && isset($this->data['base_type'])
                        ? 'Search For : ' . ProductTypeCast::from($this->data['base_type'])->getLabel()
                        : 'Search For : Choose Option';
                })

                ->columnSpanFull()->live(),
        ];
    }




    public function getProductSchema(Get $get): array
    {
        if (empty($get('base_type')))
        {
            return [];
        }


        $products = Product::with([
            'media' => fn($query) => $query->where('collection_name','displayImage')
        ])
            ->where('type',$get('base_type'))
            ->get();



        return [
            Picker::make('product_id')
                ->label('Choose Product')
                ->columns(3)
                ->columnSpanFull()
                ->options(function () use($products){
                    return $products->mapWithKeys(function ($product){
                       return [
                           $product->id => $product->name .' '.Money::format($product->price)
                       ];
                    });
                })
                ->imageSize(250)
                ->images(function () use($products) {
                    return $products->mapWithKeys(function ($product) {
                        return [
                            $product->id => $product->getFirstMediaUrl('displayImage')
                        ];
                    })->toArray();
                })

                ->extraAttributes(['class' => 'mx-auto text-lg text-center font-semibold'])
                ->default('ship'),
        ];
    }













    public function getOldProductSchema(): array
    {
        return [

            Select::make('project_id')
                ->label('Choose Project')
                ->live()
                ->options(Project::where('status',true)->get()->pluck('name','id')),

            Select::make('product_id')
                ->label('Choose Product')
                ->options(fn(Get $get) => $get('project_id') ? Product::where('project_id',$get('project_id'))->get()->pluck('name','id') : []),


            Select::make('plan_id')
                ->label('Choose Plan')
                ->live()
                ->options(Plan::all()->pluck('name','id')),

            Select::make('durations')
                ->options([
                    '3' => '3 Months',
                    '6' => '6 Months',
                    '12' => '12 Months',
                    '18' => '18 Months',
                    '24' => '24 Months',
                    '36' => '36 Months',
                ])
                ->label('Duration')
                ->placeholder('Select a duration')
                ->required()



        ];
    }



    public function getConfigurationFormSchema(): array
    {
        return [

            TextInput::make('domain')
                ->prefix('https://')
                ->placeholder('Enter your domain url')
                ->url(),


        ];
    }




    public function getPlaceOrderFormSchema(): array
    {
        return [

            Livewire::make(OrderPreviewTable::class)
                ->data(function (Get $get){

                    return [
                        'plan' =>  $selectedPlan = Plan::firstWhere('id', $get('plan_id')),
                        'duration' => $get('duration'),
                        'product' => Product::firstWhere('id',$get('product_id'))
                    ];
                })




        ];
    }




    protected function getDeck(string $name,array $options,array $descriptions,array $icons): RadioDeck
    {
        return RadioDeck::make($name)
            ->options($options)
            ->descriptions($descriptions)
            ->icons($icons)
            ->required()
            ->iconSize(IconSize::Large) // Small | Medium | Large | (string - sm | md | lg)
            ->iconSizes([ // Customize the values for each icon size
                'sm' => 'h-12 w-12',
                'md' => 'h-14 w-14',
                'lg' => 'h-16 w-16',
            ])
            ->iconPosition(IconPosition::Before) // Before | After | (string - before | after)
            ->alignment(Alignment::Center) // Start | Center | End | (string - start | center | end)
            ->gap('gap-5') // Gap between Icon and Description (Any TailwindCSS gap-* utility)
            ->padding('px-4 px-6') // Padding around the deck (Any TailwindCSS padding utility)
            ->direction('column') // Column | Row (Allows to place the Icon on top)
            ->extraCardsAttributes([ // Extra Attributes to add to the card HTML element
                'class' => 'rounded-xl'
            ])
            ->extraOptionsAttributes([ // Extra Attributes to add to the option HTML element
                'class' => 'text-3xl leading-none w-full flex flex-col items-center justify-center p-4'
            ])
            ->extraDescriptionsAttributes([ // Extra Attributes to add to the description HTML element
                'class' => 'text-sm font-light text-center'
            ])
            ->color('primary') // supports all color custom or not
            //->multiple() // Select multiple card (it will also returns an array of selected card values)
            ->columns(2)
            ->extraAttributes(['class' => 'custom'])
            ->hintActions([
                Action::make('reset')
                    //->action(fn() => $this->data[$name] = null)
                    ->action(fn() => $this->reset('data'))
            ]);

    }




}
