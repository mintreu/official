<?php

namespace App\Http\Controllers;

use App\Models\Product\Product;
use App\Models\User;
use App\Services\LicenseManagerService;
use Illuminate\Http\Request;

class TestController extends Controller
{



    public function index()
    {

        $licence = LicenseManagerService::make()
            ->user(User::find(1))
            ->product(Product::find(1))
            ->generate()->getLicense();

        dd(
            $licence,
            LicenseManagerService::make()->decompile($licence)

        );
    }



}
