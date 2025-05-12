<?php

namespace App\Livewire\Filament;

use App\Models\Product\Product;
use App\Models\Subscription\Plan;
use Livewire\Component;

class OrderPreviewTable extends Component
{

    public ?Plan $plan = null;
    public $duration;
    public ?Product $product = null;



    public function render()
    {
      //  dd($this);
        return view('livewire.filament.order-preview-table');
    }
}
