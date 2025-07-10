<?php

namespace App\Livewire\Backup\Welcome;

use App\View\Components\Layout\AppLayout;
use Livewire\Component;

class TermsAndConditionPage extends Component
{
    public function render()
    {
        return view('livewire.welcome.terms-and-condition-page')->layout(AppLayout::class);
    }
}
