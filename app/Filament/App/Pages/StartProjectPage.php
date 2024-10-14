<?php

namespace App\Filament\App\Pages;

use App\Filament\Common\Pages\Abstract\ExtendedPage;
use App\Filament\Common\Pages\XPage\XPage;
use App\Models\Enums\ProductTypeCast;
use App\Models\Product\Product;
use App\Models\Subscription\Plan;
use App\Services\MoneyService\Money;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Icetalker\FilamentPicker\Forms\Components\Picker;
use Illuminate\Database\Eloquent\Model;

class StartProjectPage extends XPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Start a New Project';
    protected static ?string $title = 'New Project';
    protected static ?string $slug = 'new-project';
    protected static bool $shouldRegisterNavigation = false;


    public ?Model $product = null;
    public ?Model $plan = null;
    public $template;


    public function mount()
    {
        $this->product = Product::firstWhere('url',request()->get('product'));
        abort_unless($this->product,404);
        $this->plan = Plan::firstWhere('url',request()->get('plan'));
        abort_unless($this->plan,404);

        if ($this->product->type == ProductTypeCast::API)
        {
            $this->template = $templates = Product::where('project_id',$this->product->project_id)
                ->where('type',ProductTypeCast::TEMPLATE)
                ->get();
        }

        $this->form->fill([
            'durations' => 12
        ]);

    }


    protected function getPageType(): string
    {
        return self::SHOW_BOTH;
    }



    public function infolist(Infolist $infolist):Infolist
    {
        return $infolist->schema([
            \Filament\Infolists\Components\Section::make('Selected Service')
                ->aside()
                ->schema([
                   Fieldset::make('Selected Product')
                        ->schema([
                            TextEntry::make('product')
                                ->inlineLabel()
                                ->getStateUsing(fn() => $this->product->name),

                            TextEntry::make('product_type')
                                ->label('Type')
                                ->inlineLabel()
                                ->badge()
                                ->icon(false)
                                ->getStateUsing(fn() => $this->product->type),

                            TextEntry::make('product_desc')
                                ->hiddenLabel()
                                ->alignJustify()
                                ->columnSpanFull()
                                ->getStateUsing(fn() => $this->product->short_desc),
                        ]),

                    Fieldset::make('Selected Plan')
                        ->schema([
                            TextEntry::make('plan')
                                ->inlineLabel()
                                ->getStateUsing(fn() => $this->plan->name),

                            TextEntry::make('plan_desc')
                                ->hiddenLabel()
                                ->columnSpanFull()
                                ->alignJustify()
                                ->getStateUsing(fn() => $this->plan->desc),
                        ])
                ]),

        ]);
    }


    public function form(Form $form): Form
    {
        return $form
            ->model($this->product)
            ->schema($this->getStartProjectFormSchema());
    }


    protected function getStartProjectFormSchema():array
    {
        return [

            Section::make('Choose Template')
                ->aside()
                ->visible(fn() => $this->product->type == ProductTypeCast::API && !is_null($this->template))
                ->schema([

                    Picker::make('product_id')
                        ->label('Choose Template')
                        ->columns(3)
                        ->columnSpanFull()
                        ->options(function () {

                            return $this->template->mapWithKeys(function ($product){
                                return [
                                    $product->id => $product->name .' '.Money::format($product->price)
                                ];
                            });
                        })
                        ->imageSize(250)
                        ->images(function ()  {
                            return $this->template->mapWithKeys(function ($product) {
                                return [
                                    $product->id => $product->getFirstMediaUrl('displayImage')
                                ];
                            })->toArray();
                        })

                        ->extraAttributes(['class' => 'mx-auto text-lg text-center font-semibold'])
                        ->default('ship'),

                ]),

            Section::make('Configuration')
                ->aside()
                ->schema([

                    TextInput::make('domain')
                        ->prefix('https://')
                        ->required(),


                    Select::make('durations')
                        ->options([
                            3 => '3 Months',
                            6 => '6 Months',
                            12 => '12 Months',
                            18 => '18 Months',
                            24 => '24 Months',
                            36 => '36 Months',
                        ])
                        ->label('Duration')
                        ->live()
                        ->placeholder('Select a duration')
                        //->afterStateUpdated(fn($state,Set $set) => $set('order_amount',$state * $this->plan->price))
                        ->afterStateUpdated(function ($state,Set $set){
                          dd($state,$this->plan->price);
                        })
                        ->required(),

                    Placeholder::make('order_amount')
                        ->content(function (Get $get){
                           return Money::format(($get('durations') ?? 0 ) * $this->plan->price). ' Only';
                        }),
                ]),



        ];
    }


}
