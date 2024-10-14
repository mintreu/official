<?php

namespace App\Filament\Common\Schemas\Product;

use App\Models\Enums\ProductTypeCast;
use App\Models\Project\Project;
use App\Services\MoneyService\Money;
use App\Services\ProductService\ProductImporter;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;


trait ProductFormSchema
{

    protected ?ProductImporter $importer = null;
    public array $versions = [];

    protected function getWelcomeFormSchema():array
    {
        return [

            Forms\Components\Section::make('Introduction')
                ->aside()
                ->description(fn() => $this->getIntroductionDescription())
                ->schema([

                    Forms\Components\TextInput::make('product_api_url')
                        ->label(__('Choose Your Project Api Url'))
                        ->columnSpanFull()
                        ->lazy()
                        ->suffixIconColor('primary')
                        ->inlineSuffix()
                        ->required()
                        ->placeholder('Type Project Url')
                        ->afterStateUpdated(function ($state){
                            $this->importer = ProductImporter::make($state);
                        })
                        ->helperText('Project Home Url eg: example.com/api'),

                    Forms\Components\Select::make('type')
                        ->label(__('Product Type'))
                        ->prefixIcon('heroicon-m-adjustments-horizontal')
                        ->options(
                            collect(ProductTypeCast::cases())->mapWithKeys(function (ProductTypeCast $type) {
                                return [$type->value => $type->getLabel()];
                            })->toArray()
                        )
                        ->required(),


                ])


        ];
    }


    public function getVersionSchema(): array
    {
        return [
            Section::make('Version Detail')
                ->aside()
                ->description(function (){
                    return new HtmlString('

                    <svg class="w-full p-2" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"  viewBox="0 0 973.4614 587.02513" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="f9b32178-6d95-472d-b482-6662972666de" data-name="Group 100"><path id="f4b94a19-cbe0-459a-95a6-6ea3b3d72055-146" data-name="Path 1465" d="M675.14672,628.10046a11.49,11.49,0,1,0,11.49,11.49h0A11.49,11.49,0,0,0,675.14672,628.10046Zm0,18.561a7.071,7.071,0,1,1,7.071-7.071v.00006A7.071,7.071,0,0,1,675.14672,646.66143Z" transform="translate(-113.2693 -156.48744)" fill="#e4e4e4"/><path id="abc0e0b3-f22e-4bee-9929-c6879640924e-147" data-name="Path 1467" d="M630.95367,619.26144h44.193v43.739a5.757,5.757,0,0,1-5.757,5.757h-32.679a5.757,5.757,0,0,1-5.757-5.757V619.26144Z" transform="translate(-113.2693 -156.48744)" fill="#e4e4e4"/></g><path id="e24b6f19-a0a0-4e73-9cfc-bbb7635ad236-148" data-name="Path 944" d="M778.40266,669.75845h-664.237a1.006,1.006,0,0,1,0-2h664.237a1.006,1.006,0,0,1,0,2Z" transform="translate(-113.2693 -156.48744)" fill="#cacaca"/><polygon points="89.41 494.381 85.302 514.694 161.695 540.208 167.759 510.228 89.41 494.381" fill="#ffb6b6"/><path d="M210.35961,647.029l-8.09119,40.00293-.00033.00162a26.01065,26.01065,0,0,1-30.64977,20.3355l-.82841-.1676,13.24784-65.49629Z" transform="translate(-113.2693 -156.48744)" fill="#2f2e41"/><circle id="fd4e5ab9-ffc8-4bbb-9267-3af70c31dae1" data-name="Ellipse 276" cx="175.97938" cy="162.713" r="51.871" fill="#feb8b8"/><path id="a8c9bbe2-1461-49cf-8211-f8d251166dc5-149" data-name="Path 1461" d="M337.69671,274.45043a59.90714,59.90714,0,0,0-34.08105-26.052l-6.368,4.647v-6.034a55.143,55.143,0,0,0-10.3-.6l-5.494,4.971v-4.558a59.33506,59.33506,0,0,0-40.932,24.347c-11.931,17.2-13.945,41.129-2.21,58.467,3.221-9.9,7.131-19.189,10.353-29.088a29.29917,29.29917,0,0,0,7.633.037l3.919-9.145,1.095,8.758c12.148-1.058,30.166-3.382,41.682-5.511l-1.12-6.719,6.7,5.583c3.528-.812,5.623-1.549,5.45-2.112,8.565,13.808,19.047,22.627,27.611,36.435C344.88769,308.40645,348.63171,292.21742,337.69671,274.45043Z" transform="translate(-113.2693 -156.48744)" fill="#2f2e41"/><ellipse id="f6be96d1-713f-4c17-812c-ecd79c56fe95" data-name="Ellipse 260" cx="192.85638" cy="464.78699" rx="133.56" ry="37.359" fill="#2f2e41"/><path d="M400.8085,452.58848c-1.78369-10.731-3.62354-21.668-8.61084-31.335-3.27784-6.332-8.481-12.366-15.49512-13.618a14.98964,14.98964,0,0,1-4.03467-.934c-2.05517-.99-29.67334-16.756-34.063-19.502-3.769-2.35736-9.71875-6.72223-12.71875-6.72223-3.01806-.06634-14.59082,2.62708-59.76025-.90381a163.14109,163.14109,0,0,0-22.01025,10.82172c-.14551-.09589-46.88184,25.00134-48.6377,24.9353-3.3252-.14-6.416,1.989-8.32227,4.67005-1.90576,2.681-2.7998,6-3.73584,9.209,10.208,22.733,19.54786,45.495,29.75684,68.228a5.82135,5.82135,0,0,1,.73828,2.8,6.83358,6.83358,0,0,1-1.27,2.8c-5.00635,8.042-4.84717,18.157-4.30029,27.609.54736,9.452,1.22412,19.436-3,27.908-1.14893,2.326-2.64307,4.455-3.73584,6.781-2.55908,5.268-3.48389,22.117-1.98926,27.777l187.2666,5.3648C382.85977,587.40928,400.8085,452.58848,400.8085,452.58848Z" transform="translate(-113.2693 -156.48744)" fill="#a463ff"/><path id="eb41f72e-aba6-4a16-bf55-c7027d2f1a4b-150" data-name="Path 1421" d="M167.23268,498.44541a33.06266,33.06266,0,0,0-.112,8.154l2.684,38.546c.252,3.633.5,7.257.841,10.881.644,7.033,1.606,14.01,2.8,20.977a3.736,3.736,0,0,0,3.829,3.633c11.815,2.5,24.022,2.4,36.08,1.719,18.4-1.027,65.474-2.97,68.35-6.706s1.2-9.779-2.55-12.8-65.871-10.386-65.871-10.386c.607-4.81,2.438-9.34,4.175-13.907,3.12-8.1,6.034-16.466,6.09-25.143s-3.213-17.8-10.321-22.771c-5.847-4.081-13.375-4.838-20.5-4.67-5.184.149-14.141-1.093-18.876.934C170.10469,488.55644,167.93268,494.77647,167.23268,498.44541Z" transform="translate(-113.2693 -156.48744)" fill="#fbbebe"/><path id="a363f2f7-2464-40a1-ad01-344825aa0b75-151" data-name="Path 1423" d="M401.14067,585.57847c3.11-1.186,6.538-.757,9.872-.308,10.732,1.466,21.622,2.97,31.569,7.257,4.67,2,9.116,4.67,12.273,8.6,2.8,3.54,4.436,7.874,5.987,12.142l3.587,9.826a47.30032,47.30032,0,0,1,3.129,11.516c1.121,11.759-6.921,22.845-17.036,28.954s-22.023,8.284-33.624,10.563-23.247,4.8-34.8,7.472a140.08632,140.08632,0,0,1-16.373,3.222c-12.366,1.4-25.292-.663-37.042,3.428-4.67,1.635-9.256,4.063-14.1,5.193a87.74454,87.74454,0,0,1-9.751,1.485l-22.238,2.54a121.31294,121.31294,0,0,1-13.636,1.046c-9.713,0-19.268-2.3-28.7-4.6a7.25771,7.25771,0,0,1-2.8-1.13c-1.494-1.177-1.821-3.26-2.036-5.146q-1.429-12.525-2.5-25.077c-.224-2.718-.392-5.66,1.093-7.948,1.868-2.8,5.511-3.68,8.836-4.222a230.57994,230.57994,0,0,1,42.665-2.961c5.426-5.426,14.374-5.454,21.482-8.406a63.89712,63.89712,0,0,0,7.575-4.119,99.00057,99.00057,0,0,1,41.59-13.141,38.71437,38.71437,0,0,0,8.509-1.214c5.5-1.709,10.517-6.361,16.167-5.221.7-1.933,1.186-4.521,2.8-5.847.8-.682,1.793-1.139,2.531-1.868,1.56-1.578,1.7-4.063,1.466-6.267s-.757-4.464-.187-6.613a9.14077,9.14077,0,0,1,1.037-2.307C391.27767,587.59544,395.50869,585.11143,401.14067,585.57847Z" transform="translate(-113.2693 -156.48744)" fill="#2f2e41"/><path id="b53ad979-99f4-4c6e-a0f8-957060c42096-152" data-name="Path 1430" d="M182.87968,429.90143a9.66706,9.66706,0,0,0-2.073,3.316,156.78038,156.78038,0,0,0-13.169,53.816,5.38,5.38,0,0,1-.607,2.606,11.40276,11.40276,0,0,1-1.373,1.485,5.156,5.156,0,0,0,.61662,7.26557q.12733.10739.26138.20642c1.541-2.9,5.23-3.848,8.509-4.1,15.7-1.242,31.036,6.062,46.783,5.511-1.111-3.839-2.709-7.528-3.615-11.413-4.007-17.251,5.987-36.033-.14-52.649-1.224-3.325-3.269-6.594-6.491-8.051a17.29051,17.29051,0,0,0-4.156-1.1c-3.979-.719-11.9-3.792-15.747-2.559-1.42.458-1.98,1.793-3.157,2.6C186.73368,427.97745,184.36169,428.39743,182.87968,429.90143Z" transform="translate(-113.2693 -156.48744)" fill="#a463ff"/><path id="a1197c30-bfd4-4098-bcf9-2468e7a33bf1-153" data-name="Path 1421" d="M400.92057,486.90641c-4.735-2.027-13.692-.785-18.876-.934-7.125-.168-14.653.589-20.5,4.67-7.108,4.971-10.377,14.094-10.321,22.771s2.97,17.043,6.09,25.143c1.737,4.567,3.568,9.097,4.175,13.907,0,0-62.121,7.365-65.871,10.386s-5.426,9.064-2.55,12.8,92.615,7.487,104.43,4.987a3.736,3.736,0,0,0,3.829-3.633c1.194-6.967,2.156-13.944,2.8-20.977.341-3.624.589-7.248.841-10.881l2.684-38.546a33.06364,33.06364,0,0,0-.112-8.154C406.83959,494.77647,404.66758,488.55644,400.92057,486.90641Z" transform="translate(-113.2693 -156.48744)" fill="#fbbebe"/><path id="ebe46053-6aac-4aa7-8615-21a6fa79538d-154" data-name="Path 1430" d="M386.25057,426.83542c-1.177-.807-1.737-2.142-3.157-2.6-3.847-1.233-11.768,1.84-15.747,2.559a17.29056,17.29056,0,0,0-4.156,1.1c-3.222,1.457-5.267,4.726-6.491,8.051-6.127,16.616,3.867,35.398-.14,52.649-.906,3.885-2.504,7.574-3.615,11.413,15.747.551,31.083-6.753,46.783-5.511,3.279.252,6.968,1.2,8.509,4.1q.134-.099.26138-.20642a5.156,5.156,0,0,0,.61663-7.26557,11.40137,11.40137,0,0,1-1.373-1.485,5.37993,5.37993,0,0,1-.607-2.606,156.78014,156.78014,0,0,0-13.169-53.816,9.66706,9.66706,0,0,0-2.073-3.316C390.41058,428.39743,388.03858,427.97745,386.25057,426.83542Z" transform="translate(-113.2693 -156.48744)" fill="#a463ff"/><polygon points="299.243 523.378 296.513 543.921 215.975 543.166 220.005 512.846 299.243 523.378" fill="#ffb6b6"/><path d="M433.12244,677.27239l-8.804,66.24017-.83783-.11132a26.01064,26.01064,0,0,1-22.35645-29.20839l.00022-.00163,5.3773-40.45722Z" transform="translate(-113.2693 -156.48744)" fill="#2f2e41"/><path id="a0b0f7ea-4ed9-4447-aa64-e2335b2c3196-155" data-name="Path 1427" d="M223.03869,595.97441c0,.934-1.466,0-1.6-1-1.027-7.556-7.024-13.786-14.01-16.812s-14.944-3.185-22.509-2.466c-11.441,1.083-24.284,5.464-28.468,16.158-.99,2.5-10.264,24.76-9.359,29.766,2.438,13.356,11.572,24.779,22.64,32.69s24,12.637,36.9,16.9a639.94948,639.94948,0,0,0,104.607,24.984c5.753.869,11.563,1.672,17.073,3.521,4.931,1.653,9.545,4.128,14.365,6.071,11.208,4.5,23.266,6.108,35.211,7.687,2.036.262,4.371.448,5.875-.934,1.943-1.8,1.3-4.941.747-7.528a40.488,40.488,0,0,1,4.773-28.729c2-3.362,4.6-6.809,4.222-10.7-.448-4.539-4.969-7.752-9.471-8.481s-9.078.346-13.6.934a7.285,7.285,0,0,1-4.623-.458,9.23483,9.23483,0,0,1-1.812-1.606,20.482,20.482,0,0,0-23.854-3.334c-1.672.934-4.2-1.149-5.94-1.933l-19.642-8.892c-22.565-10.208-45.242-20.464-69.115-26.927a14.51449,14.51449,0,0,1-5.324-2.214c-1.186-.934-2.036-2.2-3.129-3.241-2.382-2.251-5.7-3.148-8.555-4.745S222.38468,599.17742,223.03869,595.97441Z" transform="translate(-113.2693 -156.48744)" fill="#2f2e41"/><circle cx="160.61683" cy="411.98984" r="14" fill="#fbbebe"/><circle cx="191.61683" cy="411.98984" r="14" fill="#fbbebe"/><path d="M391.30469,600.02441H198.96582a8.4798,8.4798,0,0,1-8.47021-8.46972V474.86816a8.4798,8.4798,0,0,1,8.47021-8.46972H391.30469a8.4798,8.4798,0,0,1,8.47021,8.46972V591.55469A8.4798,8.4798,0,0,1,391.30469,600.02441Z" transform="translate(-113.2693 -156.48744)" fill="#3f3d56"/><circle id="e793da3b-cee2-4a15-ae11-6dab45a6c728" data-name="Ellipse 263" cx="181.39939" cy="376.72401" r="5.604" fill="#fff"/><path d="M981.29873,366.93967H731.40591a5.00573,5.00573,0,0,1-5-5V314.83958h2v47.10009a3.00328,3.00328,0,0,0,3,3H981.29873a3.00328,3.00328,0,0,0,3-3V314.51048h2v47.42919A5.00573,5.00573,0,0,1,981.29873,366.93967Z" transform="translate(-113.2693 -156.48744)" fill="#3f3d56"/><rect x="371.39686" y="157.35214" width="587.21997" height="2" fill="#3f3d56"/><circle id="a67a4451-c7cc-402c-b29d-5a705f3c9418" data-name="Ellipse 264" cx="371.4394" cy="158.196" r="17.202" fill="#a463ff"/><circle id="abdb3fbf-f68e-433e-a33f-153626b38c76" data-name="Ellipse 266" cx="546.24439" cy="158.196" r="17.202" fill="#a463ff"/><circle id="ac293633-0933-4e21-a8aa-bc27ca4fbccc" data-name="Ellipse 268" cx="664.87641" cy="208.75701" r="17.202" fill="#3f3d56"/><circle id="a0cd768d-b738-4d4b-9ab3-78ab535c7573" data-name="Ellipse 269" cx="755.06336" cy="208.75701" r="17.202" fill="#ccc"/><circle id="b9eb3d2e-31e0-4fe2-be5c-79abb86bc227" data-name="Ellipse 270" cx="711.36035" cy="158.196" r="17.202" fill="#a463ff"/><circle id="aa04baac-fce0-420a-b86a-4a97f3079928" data-name="Ellipse 271" cx="956.2594" cy="158.196" r="17.202" fill="#a463ff"/><circle id="e92cf92c-058e-415c-ba96-766a57ee18d9" data-name="Ellipse 272" cx="872.02936" cy="158.196" r="17.202" fill="#a463ff"/><rect id="ac5bf8cc-574d-4509-9338-881c836fd832" data-name="Rectangle 207" x="648.5394" width="31.90646" height="21.98984" fill="#3f3d56"/><rect id="ad5a043d-52f1-4931-b771-e4991ef7f7f7" data-name="Rectangle 208" x="355.61211" width="31.90646" height="21.98984" fill="#a463ff"/><rect id="e5d95e83-bdd9-4322-95a9-5a9c4b449a8f" data-name="Rectangle 209" x="463.68483" width="31.90646" height="21.98984" fill="#ccc"/><path d="M538.05142,315.83958a1.00005,1.00005,0,0,1-1-1V265.47727a5.00573,5.00573,0,0,1,5-5h48.6748a1,1,0,0,1,0,2h-48.6748a3.00328,3.00328,0,0,0-3,3v49.36231A1.00006,1.00006,0,0,1,538.05142,315.83958Z" transform="translate(-113.2693 -156.48744)" fill="#3f3d56"/><circle id="b0a7c1c1-8f27-441f-a84a-e44462cc473e" data-name="Ellipse 265" cx="479.74439" cy="104.61599" r="17.202" fill="#e6e6e6"/><path d="M923.05142,314.83958h-2V265.47727a5.00573,5.00573,0,0,1,5-5h48.6748v2h-48.6748a3.00328,3.00328,0,0,0-3,3Z" transform="translate(-113.2693 -156.48744)" fill="#3f3d56"/><circle id="b3140cf1-cdb8-48ff-81af-ce41ed4f5739" data-name="Ellipse 265" cx="863.74439" cy="104.61599" r="17.202" fill="#e6e6e6"/></svg>
                    ');
                })
                ->schema([
                    Forms\Components\Select::make('version')
                        ->label(__('Set Version'))
                        ->prefixIcon('heroicon-s-square-2-stack')
                        ->options($this->versions)
                        ->live()
                        ->required()
                ])->columnSpanFull(),
        ];
    }


    public function getProductFormSchema():array
    {
        return [

            Grid::make(1)
                ->columnSpan(1)
                ->schema([

                    Forms\Components\TextInput::make('name')->prefixIcon('heroicon-s-square-2-stack'),
                    Forms\Components\TextInput::make('url')->prefixIcon('heroicon-s-square-2-stack'),
                    Select::make('project_id')
                        ->label(__('Choose Project Type'))
                        ->placeholder(__('Select a project'))
                        ->prefixIcon('heroicon-s-square-2-stack')
                        ->options(Project::all()->pluck('name','id')->toArray())
                        ->nullable(),

                ]),

            SpatieMediaLibraryFileUpload::make('displayImage')
                ->collection('displayImage')
                ->columnSpan(1)
                ->required(),


            Forms\Components\RichEditor::make('desc')
                ->columnSpanFull(),
        ];
    }



    public function getBillingFormSchema():array
    {
        return [

            Section::make('Billing Information')
                ->aside()
                ->columns()
                ->columnSpanFull()
                ->visible(fn(Get $get) => $get('type') == ProductTypeCast::STANDARD->value)
                ->schema([
                    TextInput::make('base_price')->label('Base Price'),
                    TextInput::make('tax_percent')->label('Tax Percentage'),
                    TextInput::make('tax_amount')->label('Tax Amount'),
                    TextInput::make('price')->label('Price'),
                ]),


            Forms\Components\Fieldset::make('Plan And Billing')
                ->visible(fn(Get $get) => $get('type') == ProductTypeCast::API->value)
                ->columns(2)
                ->schema([

                    Grid::make(1)
                        ->schema([
                            Select::make('plan_id')
                                ->label(__('Choose Plan'))
                                ->prefixIcon('heroicon-o-sparkles')
                                ->options($this->plans)
                                ->live()
                                ->required(),

                            Select::make('duration')
                                ->options($this->durations)
                                ->prefixIcon('heroicon-s-calendar-date-range')
                                ->live()
                                ->required(),
                        ])->columnSpan(1),

                    Forms\Components\Placeholder::make('bill_detail')
                        ->hiddenLabel()
                        ->content(function (Get $get) {
                            $content = '';
                            $plan = $get('plan_id');
                            $duration = $get('duration');
                            $errors = [];

                            // Check if plan is missing
                            if (empty($plan)) {
                                $errors[] = 'No plan selected!';
                            }

                            // Check if duration is missing
                            if (empty($duration)) {
                                $errors[] = 'No duration selected!';
                            }

                            // If there are errors, display them in a user-friendly format
                            if (!empty($errors)) {
                                $content = '
                <div class="p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Billing Information Incomplete</h3>
                    <ul class="list-disc list-inside">';
                                foreach ($errors as $error) {
                                    $content .= '<li>' . $error . '</li>';
                                }
                                $content .= '</ul></div>';
                            }

                            // If both plan and duration are selected, display the billing details
                            if (!empty($plan) && !empty($duration)) {
                                $costPerMonth = new Money($this->prices[$plan], $this->currency);
                                $total = $costPerMonth->multiply((int) $duration);

                                $content = '
                <div class="p-4 border border-gray-300 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Billing Details</h3>
                    <div class="mb-2">
                        <span class="font-medium">Cost per Month:</span>
                        <span>' . $costPerMonth->formatted() . '</span>
                    </div>
                    <div class="mb-2">
                        <span class="font-medium">Selected Duration:</span>
                        <span>' . $duration . ' month(s)</span>
                    </div>
                    <div class="mb-2">
                        <span class="font-medium">Total:</span>
                        <span class="text-green-600 font-semibold">' . $total->formatted() . '</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <span>Formula:</span>
                        <span>' . $costPerMonth->formatted() . ' x ' . $duration . ' = ' . $total->formatted() . '</span>
                    </div>
                </div>
            ';
                            }

                            return new HtmlString($content);
                        })


                ]),



            Toggle::make('chargeable')->default(true),
            Toggle::make('status')->default(true),


        ];
    }




    /**
     * Section Description
     */

    protected function getIntroductionDescription(): HtmlString
    {
        return new HtmlString('
                <svg  class="w-96 h-96 p-2" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 863 826.71426" xmlns:xlink="http://www.w3.org/1999/xlink"><title>laravel and vue</title><path d="M908.58458,346.02969C863.66819,169.40825,708.46793,41.91226,526.2964,36.79851,429.98777,34.095,328.64421,66.0363,254.85863,177.81049c-131.89969,199.80845,8.10555,337.88223,105.7135,403.49213a610.75776,610.75776,0,0,1,126.24112,113.4651c65.931,78.23772,192.76719,175.45908,343.82337,23.12816C940.12233,607.48624,938.50333,463.677,908.58458,346.02969Z" transform="translate(-168.5 -36.64287)" fill="#f2f2f2"/><path d="M1030.5,739.35713c0,43.35436-189.18913,124-427.5,124s-434.5-75.64564-434.5-119,196.18913-38,434.5-38S1030.5,696.00278,1030.5,739.35713Z" transform="translate(-168.5 -36.64287)" fill="#3f3d56"/><path d="M1030.5,739.35713c0,43.35436-189.18913,124-427.5,124s-434.5-75.64564-434.5-119,196.18913-38,434.5-38S1030.5,696.00278,1030.5,739.35713Z" transform="translate(-168.5 -36.64287)" opacity="0.1"/><ellipse cx="431.5" cy="707.21426" rx="431.5" ry="78.5" fill="#3f3d56"/><polygon points="594.859 440.238 594.859 650.182 629.976 707.413 632.649 711.766 771.212 711.766 774.011 440.238 594.859 440.238" fill="#2f2e41"/><polygon points="594.859 440.238 594.859 650.182 629.976 707.413 632.131 440.238 594.859 440.238" opacity="0.1"/><polygon points="260.348 434.639 260.348 644.583 225.231 701.814 222.558 706.167 83.994 706.167 81.195 434.639 260.348 434.639" fill="#2f2e41"/><polygon points="260.348 434.639 260.348 644.583 225.231 701.814 223.075 434.639 260.348 434.639" opacity="0.1"/><polygon points="816 430.44 816 443.037 63 443.037 63 424.842 132.981 388.452 754.416 388.452 816 430.44" fill="#2f2e41"/><polygon points="816 430.44 816 443.037 63 443.037 63 424.842 816 430.44" opacity="0.1"/><polygon points="170.771 493.424 137.18 493.424 123.184 483.626 186.167 483.626 170.771 493.424" opacity="0.1"/><polygon points="170.771 534.013 137.18 534.013 123.184 524.215 186.167 524.215 170.771 534.013" opacity="0.1"/><polygon points="170.771 591.398 137.18 591.398 123.184 581.6 186.167 581.6 170.771 591.398" opacity="0.1"/><polygon points="170.771 648.782 137.18 648.782 123.184 638.985 186.167 638.985 170.771 648.782" opacity="0.1"/><polygon points="684.435 499.022 718.026 499.022 732.022 489.225 669.039 489.225 684.435 499.022" opacity="0.1"/><polygon points="684.435 539.611 718.026 539.611 732.022 529.814 669.039 529.814 684.435 539.611" opacity="0.1"/><polygon points="684.435 596.996 718.026 596.996 732.022 587.199 669.039 587.199 684.435 596.996" opacity="0.1"/><polygon points="684.435 654.381 718.026 654.381 732.022 644.583 669.039 644.583 684.435 654.381" opacity="0.1"/><path d="M701.01569,390.06945,696.347,419.877s-22.98411,12.2103-6.8234,12.56943,92.29556,0,92.29556,0,14.72419,0-8.619-12.92856l-4.66865-31.244Z" transform="translate(-168.5 -36.64287)" fill="#3f3d56"/><path d="M686.27261,432.123a63.5688,63.5688,0,0,1,10.07442-6.66854l4.66865-29.80753,67.51582.06351,4.66865,29.38488a61.30417,61.30417,0,0,1,10.78841,7.09889c2.99811-.68405,6.2745-3.22612-10.78841-12.67635l-4.66865-31.244-67.51582,1.79564L696.347,419.877S677.03363,430.13851,686.27261,432.123Z" transform="translate(-168.5 -36.64287)" opacity="0.1"/><rect x="413.82432" y="145.67179" width="304.18032" height="211.88475" rx="10.6948" fill="#3f3d56"/><rect x="424.05943" y="156.98428" width="283.71009" height="158.734" fill="#f2f2f2"/><circle cx="565.91447" cy="151.9565" r="1.79563" fill="#f2f2f2"/><path d="M886.50463,364.93058v18.574a10.69346,10.69346,0,0,1-10.6948,10.69481H593.01912a10.69346,10.69346,0,0,1-10.6948-10.69481v-18.574Z" transform="translate(-168.5 -36.64287)" fill="#3f3d56"/><circle cx="565.91447" cy="343.01191" r="6.82341" fill="#f2f2f2"/><polygon points="555.281 415.196 555.281 418.788 362.071 418.788 362.071 415.915 362.337 415.196 367.099 402.268 551.331 402.268 555.281 415.196" fill="#3f3d56"/><path d="M798.13506,448.32338c-.35194,1.50472-1.68071,3.09212-4.683,4.59324-10.77381,5.3869-32.68053-1.4365-32.68053-1.4365s-16.879-2.873-16.879-10.41468a13.46778,13.46778,0,0,1,1.4796-.87984c4.52961-2.3964,19.54831-8.3097,46.17806.25038a11.10594,11.10594,0,0,1,5.06187,3.31716A5.29644,5.29644,0,0,1,798.13506,448.32338Z" transform="translate(-168.5 -36.64287)" fill="#3f3d56"/><path d="M798.13506,448.32338c-13.18712,5.05294-24.94133,5.43-37.00441-2.94842-6.0836-4.22333-11.61057-5.2684-15.75848-5.18936,4.52961-2.3964,19.54831-8.3097,46.17806.25038a11.10594,11.10594,0,0,1,5.06187,3.31716A5.29644,5.29644,0,0,1,798.13506,448.32338Z" transform="translate(-168.5 -36.64287)" opacity="0.1"/><ellipse cx="613.10087" cy="407.29559" rx="4.66865" ry="1.43651" fill="#f2f2f2"/><polygon points="555.281 415.196 555.281 418.788 362.071 418.788 362.071 415.915 362.337 415.196 555.281 415.196" opacity="0.1"/><path d="M834.19367,268.59416c-1.52438-1.52439-21.08732-26.1686-24.39014-30.23362-3.55691-4.065-5.08129-3.30284-7.36787-3.04877s-26.93078,4.57315-29.72549,4.82721c-2.7947.50813-4.57315,1.52439-2.7947,4.065,1.52438,2.28657,17.78447,25.15234,21.34137,30.48768l-64.78633,15.4979-51.32094-86.1277c-2.03251-3.04877-2.54064-4.065-7.11379-3.811s-40.39618,3.30283-42.93682,3.30283c-2.54064.25407-5.33535,1.27032-2.79471,7.36786s43.19089,93.49557,44.20714,96.03621,4.065,6.60566,10.92475,5.08128c7.1138-1.77845,31.504-8.13,44.96934-11.68694,7.11379,12.7032,21.34138,38.61773,24.13609,42.42869,3.5569,5.08128,6.09754,4.065,11.43288,2.54064,4.31909-1.27032,66.56478-23.62795,69.35948-24.89827s4.57316-2.03251,2.54064-4.82722c-1.52438-2.03251-17.78448-24.13609-26.42267-35.569,5.84349-1.52438,26.93079-7.11379,29.21737-7.876,2.54064-.76219,3.04876-2.03251,1.52438-3.5569ZM716.562,292.73024c-.76219.25406-37.09335,8.89225-38.8718,9.40038-2.03251.50813-2.03251.25406-2.03251-.50813-.50813-.76219-43.19088-89.17648-43.95307-90.19274-.50813-1.01626-.50813-2.03251,0-2.03251s34.29863-3.04877,35.31488-3.04877c1.27032,0,1.01626.25406,1.52439,1.01625,0,0,47.51,82.06268,48.27216,83.333,1.01626,1.27032.50813,1.77845-.25406,2.03251Zm102.13375,19.05481c.50812,1.01626,1.27032,1.52438-.7622,2.03251-1.77844.76219-61.22943,20.83325-62.49975,21.34137s-2.03251.76219-3.5569-1.52438-20.83326-35.569-20.83326-35.569l63.26194-16.51416c1.52438-.50813,2.03252-.76219,3.04876.76219,1.01626,1.77845,20.83325,28.70924,21.34139,29.47144Zm4.065-44.71527c-1.52438.25406-24.64421,6.09753-24.64421,6.09753l-19.0548-25.91453c-.50813-.76219-1.01626-1.52439.25406-1.77845s22.86576-4.065,23.882-4.31909,1.77844-.50813,3.04876,1.27032c1.27032,1.52438,17.53043,22.35763,18.29261,23.11982s-.25406,1.27033-1.77844,1.52439Z" transform="translate(-168.5 -36.64287)" fill="#fb503b"/><circle cx="394.63903" cy="155.89453" r="38.24668" fill="#ffb9b9"/><path d="M534.95727,211.66073v60.38949h62.40247s-8.05193-50.32457-4.026-65.42194Z" transform="translate(-168.5 -36.64287)" fill="#ffb9b9"/><path d="M658.75572,293.18654s-59.383-29.18825-59.383-31.20123-6.03894-15.09737-10.06491-15.09737-38.24668-11.07141-57.37,5.03245l-2.013,13.08439-73.47387,48.31159,20.12983,73.47388s13.08438,14.09088,9.05842,24.1558,3.01947,61.396,3.01947,61.396l158.01916-5.03245V398.86815s5.03246-16.10387,8.05194-21.13632,0-20.12983,0-20.12983l24.15579-30.19475S675.86608,299.22549,658.75572,293.18654Z" transform="translate(-168.5 -36.64287)" fill="#d0cde1"/><path d="M458.96717,337.97541l4.52921-24.659S437.3276,427.04991,440.34707,440.1343s25.16229,33.21422,28.18176,34.22071,31.20124-86.55827,31.20124-86.55827Z" transform="translate(-168.5 -36.64287)" opacity="0.1"/><path d="M473.56129,310.2969l-17.11035,3.01947S430.28216,427.04991,433.30163,440.1343s25.16229,33.21422,28.18176,34.22071,31.20124-86.55827,31.20124-86.55827Z" transform="translate(-168.5 -36.64287)" fill="#d0cde1"/><path d="M664.29142,344.01436l8.55518-16.60711s12.0779,113.73354,4.026,124.805-39.90294,15.21422-39.90294,15.21422l-.35672-82.64915Z" transform="translate(-168.5 -36.64287)" opacity="0.1"/><path d="M665.80116,314.32286l13.08439,13.08439s12.0779,113.73354,4.026,124.805-39.90294,15.21422-39.90294,15.21422l-.35672-82.64915Z" transform="translate(-168.5 -36.64287)" fill="#d0cde1"/><path d="M470.54182,487.4394s-29.18826-11.07141-34.22071,10.06491S448.399,613.25083,448.399,613.25083s11.07141,71.46089,18.11685,73.47388,38.24668,3.01947,43.27913-6.03895-15.09737-101.65564-15.09737-101.65564l116.753-2.013s-7.04544,80.51931-11.0714,87.56475-4.026,31.20124,0,31.20124,48.31159-2.013,51.33106-8.05193,20.12983-141.9153,20.12983-141.9153,14.09088-47.3051-2.013-44.28562S470.54182,487.4394,470.54182,487.4394Z" transform="translate(-168.5 -36.64287)" fill="#3f3d56"/><path d="M642.65186,683.70523s10.06491-7.04544,15.09737,0,7.04544,15.09737-15.09737,27.17527-39.25317,9.05843-39.25317,7.04544V688.73769Z" transform="translate(-168.5 -36.64287)" fill="#2f2e41"/><path d="M468.52883,680.68576s-10.06491-7.04544-15.09737,0S446.386,695.78313,468.52883,707.861s39.25317,9.05842,39.25317,7.04544V685.71822Z" transform="translate(-168.5 -36.64287)" fill="#2f2e41"/><path d="M563.38706,144.31209a32.34794,32.34794,0,0,0-11.23352,1.60112,43.42778,43.42778,0,0,0-8.00273,4.2341L527.26546,160.6475a7.90785,7.90785,0,0,0-4.76623,7.37l-2.11,15.80182c-.53248,3.98775-1.02331,8.25232.7618,11.85776,1.49,3.00929,4.3314,5.06856,6.66782,7.4804a27.456,27.456,0,0,1,7.23648,14.06091c1.20795,6.59754.43461,14.5425,5.65567,18.75287,2.827,2.27972,6.6803,2.71095,10.30576,2.92289a153.78563,153.78563,0,0,0,29.70069-1.14315,11.81912,11.81912,0,0,0,4.48748-1.25739c1.775-1.04988,2.89248-2.90316,3.93692-4.68137,3.77061-6.41971,7.657-13.24892,7.61606-20.69395-.02026-3.68318-.96268-7.6389.8058-10.8698a42.49668,42.49668,0,0,1,3.03759-3.945c2.18963-3.07677,2.61589-7.03,2.97274-10.7897,1.13978-12.00866-.05177-21.1472-8.90224-29.60633C586.41,148.011,574.65448,144.38477,563.38706,144.31209Z" transform="translate(-168.5 -36.64287)" fill="#2f2e41"/><polygon points="300.937 569.703 267.346 740.458 284.141 740.458 316.333 572.503 300.937 569.703" fill="#2f2e41"/><polygon points="408.708 583.7 429.703 779.648 443.699 779.648 431.102 578.101 408.708 583.7" fill="#2f2e41"/><polygon points="466.093 578.101 495.485 708.267 509.481 708.267 481.489 575.302 466.093 578.101" fill="#2f2e41"/><polygon points="354.123 578.101 349.924 681.674 361.121 681.674 369.519 578.101 354.123 578.101" fill="#2f2e41"/><path d="M445.64312,588.15108s-22.394-149.76023,40.58922-153.95911,152.55948,0,152.55948,0,51.78625-4.19889,36.39034,153.95911c0,0,13.99628,46.18773-111.97026,41.98884S445.64312,588.15108,445.64312,588.15108Z" transform="translate(-168.5 -36.64287)" fill="#a463ff"/><path d="M1019.5,649.30794c0,26.1153-15.52513,35.23378-34.67635,35.23378s-34.67635-9.11848-34.67635-35.23378,34.67635-59.33808,34.67635-59.33808S1019.5,623.19264,1019.5,649.30794Z" transform="translate(-168.5 -36.64287)" fill="#a463ff"/><polygon points="815.416 622.048 830.195 595.009 815.471 618.619 815.631 608.792 825.817 589.229 815.673 606.191 815.96 588.516 826.868 572.942 816.005 585.737 816.185 553.327 815.106 594.394 804.06 577.487 814.972 597.859 813.939 617.599 813.908 617.075 801.123 599.212 813.869 618.926 813.74 621.395 813.717 621.432 813.728 621.635 811.106 647.977 814.609 647.977 815.029 645.849 827.744 626.182 815.06 643.904 815.416 622.048" fill="#3f3d56"/><polygon points="838.47 704.549 797.129 704.549 786.445 644.163 846.831 644.163 838.47 704.549" fill="#2f2e41"/><rect x="145" y="296.30047" width="86" height="100.82759" fill="#3f3d56"/><rect x="158.34483" y="311.94613" width="59.31034" height="69.53627" fill="#f2f2f2"/><path d="M362.4444,363.35713l-6.02314,10.4315-6.02315-10.4315H330.34252l26.07874,45.17047L382.5,363.35713Z" transform="translate(-168.5 -36.64287)" fill="#4dba87"/><path d="M362.4444,363.35713l-6.02314,10.4315-6.02315-10.4315H340.774l15.64724,27.101,15.64724-27.101Z" transform="translate(-168.5 -36.64287)" fill="#435466"/></svg>
        ');
    }



}
