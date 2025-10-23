<?php

namespace App\Livewire\Pages\Product;

use App\Filament\Common\Resources\Studio\StudioResource;
use App\Models\Product\Product;
use App\View\Components\Layout\Frontend;
use Livewire\Component;

class ProductViewPage extends Component
{

    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product->load(['category', 'project', 'plans','data']);
    }

    public function addToCart(int $planId)
    {
        $this->redirect(StudioResource::getUrl('create').'?product_id='.$this->product->id.'&plan_id='.$planId);
    }


    public function render()
    {
        return view('livewire.pages.product.product-view-page')->layout(Frontend::class);
    }
}
