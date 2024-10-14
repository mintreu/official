<?php

namespace App\Filament\Resources\Product\ProductResource\Pages;

use App\Filament\Common\Schemas\Product\ProductFormSchema;
use App\Filament\Resources\Product\ProductResource;
use App\Services\ProductService\ProductImporter;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CreateProduct extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard,ProductFormSchema;
    protected static string $resource = ProductResource::class;
    protected static bool $canCreateAnother = false;

    public array $plans = [];
    public array $durations = [];
    public array $prices = [];
    public ?string $currency = 'INR';

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([

                Wizard::make([

                    Wizard\Step::make('Welcome')
                        ->schema(fn() => $this->getWelcomeFormSchema())
                        ->afterValidation(fn() => $this->fetchAndFillVersionInformation()),

                    Wizard\Step::make('Version')
                        ->afterValidation(fn() => $this->fetchAndFillProductInformation())
                        ->schema($this->getVersionSchema()),

                    Wizard\Step::make('Product')
                        ->columns()
                        ->schema(fn() => $this->getProductFormSchema()),

                    Wizard\Step::make('Billing')
                        ->columns()
                        ->schema($this->getBillingFormSchema()),

                    Wizard\Step::make('MetaData')
                        ->schema([


                            KeyValue::make('metadata')
                                ->keyLabel(__('Attribute Name'))
                                ->valueLabel(__('Attribute Value'))
                                ->required(),


                        ]),
                ])
                    //->skippable()
                    ->columnSpanFull()
                    ->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button
                        type="submit"
                        size="sm"
                    >
                        Submit
                    </x-filament::button>
                BLADE))),


            ]);
    }




    public function fetchAndFillVersionInformation()
    {
        $this->importer = ProductImporter::make($this->data['product_api_url']);
        $this->importer->loadVersions();
        $this->versions = $this->importer->getVersions();
        $this->form->fill([
            'product_api_url' => $this->data['product_api_url'],
            'type' => $this->data['type'],
            'version' => $this->versions
        ]);
    }

    public function fetchAndFillProductInformation()
    {
        $this->importer = ProductImporter::make($this->data['product_api_url']);
        $this->importer->loadVersionDetail($this->data['version']);
        $this->importer->loadVersionPlans($this->data['version']);
        $this->plans = $this->importer->getPlans();
        $this->durations = $this->importer->getDurations();
        $this->prices = $this->importer->getPlanPrices();
        $this->currency = $this->importer->getCurrency();
        $this->form->fill([
            'product_api_url' => $this->data['product_api_url'],
            'type' => $this->data['type'],
            'version' => $this->versions,
            //'plan_id' => $this->importer->getPlans(),
            'name' => $this->importer->getProjectName(),
            'url' => Str::slug($this->importer->getProjectName()),
            'desc' => $this->importer->getProjectDescription(),
        ]);


    }

}
