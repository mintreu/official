<?php

namespace App\Livewire\Backup\Welcome\Sections;

use App\Models\Service\Service;
use Livewire\Component;

class ServicesSection extends Component
{


    public function render()
    {
        $allServices = Service::where('status',true)->get();


        return view('livewire.welcome.sections.services-section',[
            'records' => $allServices
        ]);
    }
}
