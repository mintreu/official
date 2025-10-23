<?php

namespace App\Livewire\Pages;

use App\View\Components\Layout\AppLayout;
use App\View\Components\Layout\Frontend;
use Livewire\Component;

class Homepage extends Component
{
    public function render()
    {
        return view('livewire.pages.homepage')->layout(Frontend::class);
    }
}
