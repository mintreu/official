<?php

namespace App\Livewire\Welcome;

use App\View\Components\Layout\AppLayout;
use Livewire\Component;

class WelcomePage extends Component
{


    public $title;

    public function mount()
    {
        $this->title = 'Welcome|'.config('app.name');
    }



    public function render()
    {
        return view('livewire.welcome.welcome-page')->layout(AppLayout::class);
    }
}
