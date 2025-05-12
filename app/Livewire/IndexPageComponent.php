<?php

namespace App\Livewire;

use App\View\Components\Layout\AppLayout;
use App\View\Components\Layout\FrontLayout;
use Livewire\Component;

class IndexPageComponent extends Component
{





    public function render()
    {
        return view('livewire.index-page-component')->layout(FrontLayout::class);
    }
}
