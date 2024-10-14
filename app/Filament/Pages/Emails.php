<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Emails extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $activeNavigationIcon = 'heroicon-s-envelope';

    protected static string $view = 'filament.pages.emails';
}
