<?php

use Illuminate\Support\Facades\Route;





Route::get('/',\App\Livewire\IndexPageComponent::class);









//Route::get('/', function () {
//    return view('welcome');
//});


////Route::get('/',\App\Livewire\Welcome\WelcomePage::class);
//Route::get('/privacy',\App\Livewire\Welcome\PrivacyPolicyPage::class);
//Route::get('/terms',\App\Livewire\Welcome\TermsAndConditionPage::class);
//Route::get('/faqs',\App\Livewire\Welcome\FaqPage::class);
//
//
//Route::get('/projects/{project:url}',\App\Livewire\Welcome\Pages\ProjectViewPage::class)->name('welcome.project.view');
//Route::get('/product/{product:url}',\App\Livewire\Welcome\Pages\ProductViewPage::class)->name('welcome.product.view');

//Route::get('/',\App\Livewire\Themes\Default\Homepage::class);

//Route::get('about',\App\Livewire\Themes\Default\AboutPage::class);




Route::get('/test/testing',[\App\Http\Controllers\TestController::class,'index']);
