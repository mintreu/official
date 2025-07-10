<?php

use App\Livewire\Pages\Product\ProductIndexPage;
use Illuminate\Support\Facades\Route;


Route::get('/',\App\Livewire\Pages\Homepage::class);
Route::get('/products', ProductIndexPage::class);
Route::get('/product/{product:url}', \App\Livewire\Pages\Product\ProductViewPage::class);
