<?php

namespace App\Livewire\Backup\Welcome;

use App\View\Components\Layout\AppLayout;
use Livewire\Component;

class PrivacyPolicyPage extends Component
{
    public function render()
    {
        return view('livewire.welcome.privacy-policy-page')->layout(AppLayout::class);
    }
}
