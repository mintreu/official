<?php

namespace App\Livewire\Welcome;

use App\View\Components\Layout\AppLayout;
use Livewire\Component;

class FaqPage extends Component
{
    public function render()
    {
        return view('livewire.welcome.faq-page')->layout(AppLayout::class);
    }
}
