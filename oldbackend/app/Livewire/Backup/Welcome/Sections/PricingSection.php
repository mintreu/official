<?php

namespace App\Livewire\Backup\Welcome\Sections;

use App\Models\Subscription\Plan;
use Livewire\Component;

class PricingSection extends Component
{
    public $plans;

    public function mount()
    {
        $this->plans = Plan::where('visible_on_front',true)->get();
    }

    public function render()
    {
        return view('livewire.welcome.sections.pricing-section', [
            'plans' => $this->plans,
        ]);
    }
}
