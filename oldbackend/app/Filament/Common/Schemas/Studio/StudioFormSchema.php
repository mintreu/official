<?php

namespace App\Filament\Common\Schemas\Studio;

use App\Forms\Components\SelectPro;
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
use Illuminate\Support\Str;


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

                Wizard\Step::make('Product')
                    ->icon('heroicon-c-squares-2x2')
                    ->schema(fn() => $this->prepareProductForm()),

                Wizard\Step::make('Plan')
                    ->columns()
                    ->icon('heroicon-m-cog')
                    ->schema(fn() => $this->prepareStudioPlanAndSubscriptionForm()),
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
                            Place Order
                        </x-filament::button>
                    BLADE))),
        ];
    }




    // Sub Schemas

    public function prepareStudioForm(): array
    {
        return [

            // ---------------------- STUDIO DETAILS SECTION ---------------------- //
            Forms\Components\Section::make('Studio Details')
                ->aside()
                ->description('Provide basic information about your studio. These details will be used to create your branded space.')
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label('Studio Name')
                        ->placeholder('Enter the name of your studio')
                        ->helperText('This name will be displayed publicly and used to generate your subdomain.')
                        ->prefixIcon('heroicon-c-squares-2x2')
                        ->columnSpanFull()
                        ->lazy()
                        ->afterStateUpdated(fn(Forms\Set $set, $state) => $set('url', Str::slug($state)))
                        ->maxLength(255),

                    Forms\Components\TextInput::make('url')
                        ->required()
                        ->label('Studio Subdomain')
                        ->placeholder('your-studio-name')
                        ->prefix(config('app.url') . '/sites/')
                        //->suffix('.' . parse_url(config('app.url'), PHP_URL_HOST))
                        ->hint('This will be your unique URL inside the platform.')
                        ->helperText('Choose a short, memorable subdomain for your studio.')
                        ->columnSpanFull()
                        ->maxLength(255),
                ]),


            // ---------------------- EXTERNAL HOSTING SECTION ---------------------- //
            Forms\Components\Section::make('Custom Domain (Optional)')
                ->aside()
                ->description('Use your own domain name instead of the subdomain provided.')
                ->schema([

                    Forms\Components\TextInput::make('domain')
                        ->label('Custom Domain')
                        ->placeholder('yourdomain.com')
                        ->prefix('https://')
                        ->lazy()
                        ->helperText(fn(Forms\Get $get, $state) =>
                        $state
                            ? 'Preview URL: ' . ($get('domain_schema') ?? 'https://') . $state
                            : 'Enter your custom domain (e.g. mystudio.com)'
                        )
                        ->hint('You must point your domain’s DNS to our servers.')
                        ->suffixIcon('heroicon-o-globe-alt')
                        ->columnSpan(4)
                        ->maxLength(255),




                ])
                ->columns(4)
                ->columnSpanFull(),

        ];
    }


    public function prepareProductForm(): array
    {
        return  [
            Forms\Components\Section::make('Product Selection')
                ->description('Choose the product or service to associate with this studio.')
                ->aside()
                ->schema([

                    Forms\Components\Select::make('product_id')
                        ->label('Select Product')
                        ->placeholder('Click to select a product or service')
                        ->hint('Only active and visible products are listed')
                        ->helperText('Choose the product you want to link to this studio. Each product includes a name, type, and image.')
                        ->relationship(
                            name: 'product',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query->where('type','=',ProductTypeCast::API)->with('media')
                        )
                        ->getOptionLabelFromRecordUsing(function (Product $record) {
                            $imageUrl = $record->getFirstMediaUrl('displayImage') ?? '/default-image.jpg';
                            return <<<HTML
                            <div class="flex items-center gap-3">
                                <img src="{$imageUrl}" alt="{$record->name}" class="w-10 h-10 rounded object-cover shadow-sm border" />
                                <div class="text-left">
                                    <div class="font-semibold text-sm text-gray-800">{$record->name}</div>

                                    <div class="text-xs text-gray-500">{$record->short_desc}</div>
                                </div>
                            </div>
                        HTML;
                        })
                        ->searchable()
                        ->preload()
                        ->allowHtml() // Enables rendering HTML inside option labels
                        ->live()
                        ->required()
                        ->default(fn ($state) => $state)
                        ->disabled(fn () => !is_null($this->product))
                        ->columnSpanFull(),

                ])
        ];

    }




    protected function prepareStudioPlanAndSubscriptionForm(): array
    {
        return [
            Forms\Components\Section::make('Plan & Subscription Details')
                ->visible(fn (Forms\Get $get) => $get('product_id'))
                ->aside()
                ->description('Configure subscription plan and duration based on the selected product.')
                ->schema([

                    // ---------------- PLAN SELECT ---------------- //
                    Forms\Components\Select::make('plan_id')
                        ->label('Select a Plan')
                        ->placeholder('Choose a subscription plan')
                        ->helperText('Plans vary by product. Pricing and monthly usage limits are shown.')
                        ->relationship(
                            name: 'plan',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn (Forms\Get $get, $query) => $query
                                ? $query->when($get('product_id'), fn ($q, $productId) =>
                                $q->where('product_id', $productId)->orderByDesc('price'))
                                : $query
                        )
                        ->getOptionLabelFromRecordUsing(function (Model $record) {
                            $limitText = ($record->per_month_limit === 0 || $record->per_month_limit === null)
                                ? 'Unlimited'
                                : number_format($record->per_month_limit) . ' calls';

                            return <<<HTML
                            <div class="flex flex-col gap-1">
                                <div class="font-semibold text-sm text-gray-800">{$record->name}</div>
                                <div class="text-xs text-gray-500">Price: <strong>{$record->price}INR</strong> • Cap: {$limitText}</div>
                            </div>
                        HTML;
                        })
                        ->disabled(fn () => filled($this->plan_id))
                        ->inlineLabel()
                        ->allowHtml()
                        ->searchable()
                        ->required()
                        ->columnSpanFull(),

                    // ---------------- DURATION SELECT ---------------- //
                    Forms\Components\Select::make('duration_in_months')
                        ->label('Subscription Duration')
                        ->placeholder('Select a duration')
                        ->helperText('Choose how long the subscription will remain active.')
                        ->inlineLabel()
                        ->native(false)
                        ->allowHtml()
                        ->options([
                            1 => '<div class="flex items-center gap-2"><img src="https://placehold.co/40x40/4F46E5/FFF?text=1M&font=roboto" class="w-6 h-6 rounded" /><span>1 Month</span></div>',
                            3 => '<div class="flex items-center gap-2"><img src="https://placehold.co/40x40/10B981/FFF?text=3M&font=roboto" class="w-6 h-6 rounded" /><span>3 Months</span></div>',
                            6 => '<div class="flex items-center gap-2"><img src="https://placehold.co/40x40/F59E0B/FFF?text=6M&font=roboto" class="w-6 h-6 rounded" /><span>6 Months</span></div>',
                            12 => '<div class="flex items-center gap-2"><img src="https://placehold.co/40x40/EF4444/FFF?text=12M&font=roboto" class="w-6 h-6 rounded" /><span>12 Months</span></div>',
                            36 => '<div class="flex items-center gap-2"><img src="https://placehold.co/40x40/8B5CF6/FFF?text=36M&font=roboto" class="w-6 h-6 rounded" /><span>36 Months</span></div>',
                        ])
                        ->default(3)
                        ->required()
                        ->columnSpanFull(),

                ])
                ->columns(4)
                ->columnSpanFull(),
        ];
    }





    public function getSubscriptionFormSchema(): array
    {
        return [
            Forms\Components\Placeholder::make('overview')
                ->label('Review Your Subscription Order')
                ->content(function (Forms\Get $get) {
                    $studioName     = $get('name') ?? '—';
                    $studioUrlPart  = $get('url');
                    $customDomain   = $get('domain') ?? '—';
                    $duration       = (int)($get('duration_in_months') ?? 0);

                    $studioUrl = $studioUrlPart
                        ? config('app.url') . '/sites/' . $studioUrlPart
                        : null;

                    $studioUrlHtml = $studioUrl
                        ? '<a href="' . e($studioUrl) . '" class="text-primary-600 underline font-medium" target="_blank">' . e($studioUrl) . '</a>'
                        : '—';

                    $product = Product::with([
                        'media' => fn($q) => $q->where('collection_name', 'displayImage'),
                        'plans' => fn($q) => $q->where('id', $get('plan_id')),
                    ])->find($get('product_id'));

                    $plan = $product?->plans?->first();

                    $productImage = $product?->getFirstMediaUrl('displayImage')
                        ?? 'https://placehold.co/100x100?text=No+Image';

                    $productName = $product?->name ?? '—';
                    $productDesc = $product?->short_desc ?? '—';

                    $planName  = $plan?->name ?? '—';
                    $planPrice = $plan ? $plan->price : 0;
                    $planPriceFormatted = Money::format($planPrice);

                    $limitText = match (true) {
                        !$plan,
                            $plan?->per_month_limit === null,
                            $plan?->per_month_limit == 0 => 'Unlimited',
                        default => number_format($plan->per_month_limit) . ' calls/month',
                    };

                    $durationText = match ($duration) {
                        1 => '1 Month',
                        3 => '3 Months',
                        6 => '6 Months',
                        12 => '12 Months',
                        36 => '36 Months',
                        default => '—',
                    };

                    $totalCostFormatted = $plan ? Money::format($planPrice * $duration) : '—';

                    $badges = '';
                    if ($plan?->is_recommended) {
                        $badges .= '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 mr-1">Recommended</span>';
                    }
                    if ($plan?->is_enterprise) {
                        $badges .= '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Enterprise</span>';
                    }

                    return new HtmlString(<<<HTML
<div class="rounded-lg border shadow-sm divide-y divide-gray-200 overflow-hidden bg-white text-sm text-gray-700">

    <!-- Studio Info -->
    <div class="p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-2">Studio Information</h3>
        <dl class="space-y-1">
            <div><dt class="inline font-medium">Name:</dt> <dd class="inline">{$studioName}</dd></div>
            <div><dt class="inline font-medium">Subdomain URL:</dt> <dd class="inline">{$studioUrlHtml}</dd></div>
            <div><dt class="inline font-medium">Custom Domain:</dt> <dd class="inline">{$customDomain}</dd></div>
        </dl>
    </div>

    <!-- Product Info -->
    <div class="p-6 flex gap-4">
        <img src="{$productImage}" alt="Product Image" class="w-20 h-20 rounded-md border object-cover">
        <div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">{$productName}</h3>
            <p class="text-gray-500 text-sm">{$productDesc}</p>
        </div>
    </div>

    <!-- Plan Info -->
    <div class="p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-2">Plan Details</h3>
        <dl class="space-y-1">
            <div><dt class="inline font-medium">Plan:</dt> <dd class="inline">{$planName}</dd> {$badges}</div>
            <div><dt class="inline font-medium">Price:</dt> <dd class="inline">{$planPriceFormatted} / month</dd></div>
            <div><dt class="inline font-medium">Usage Limit:</dt> <dd class="inline">{$limitText}</dd></div>
        </dl>
    </div>

    <!-- Summary -->
    <div class="p-6 bg-gray-50">
        <h3 class="text-base font-semibold text-gray-900 mb-2">Subscription Summary</h3>
        <dl class="space-y-1">
            <div><dt class="inline font-medium">Duration:</dt> <dd class="inline">{$durationText}</dd></div>
            <div><dt class="inline font-medium">Total Cost:</dt> <dd class="inline text-gray-900">{$totalCostFormatted}</dd></div>
        </dl>
    </div>

    <!-- Footer -->
    <div class="px-6 py-4 text-right text-xs text-gray-400">
        All prices are in INR. Taxes may apply.
    </div>
</div>
HTML);
                }),
        ];
    }







}
