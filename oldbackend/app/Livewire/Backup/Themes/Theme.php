<?php

namespace App\Livewire\Backup\Themes;

use App\View\Components\Layout\DefaultLayout;
use Livewire\Component;

class Theme extends Component
{
    protected static  string $layout = DefaultLayout::class;
    protected  ?string $view = null;



    protected function getView()
    {
        return $this->view;
    }


    public function render()
    {
        return view($this->getView())->layout(self::$layout);
    }

}
