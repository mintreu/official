<?php

namespace App\Livewire\Pages\Product;

use App\Models\Category\Category;
use App\Models\Product\Product;
use App\View\Components\Layout\Frontend;
use Livewire\Component;

class ProductIndexPage extends Component
{
    public $perPage = 12;
    public $category = '';
    public $sort = 'latest';
    public $categories = [];
    public $allLoaded = false;

    protected $listeners = ['loadMore'];

    public function mount()
    {
        $this->categories = Category::pluck('name', 'id')->toArray();
    }

    public function loadMore()
    {
        if (! $this->allLoaded) {
            $this->perPage += 12;
        }
    }

    public function updatedCategory()
    {
        $this->perPage = 12;
        $this->allLoaded = false;
    }

    public function updatedSort()
    {
        $this->perPage = 12;
        $this->allLoaded = false;
    }

    public function render()
    {
        $query = Product::with(['media', 'category']);

        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        switch ($this->sort) {
            case 'az':  $query->orderBy('name', 'asc'); break;
            case 'za':  $query->orderBy('name', 'desc'); break;
            case 'plh': $query->orderBy('price', 'asc'); break;
            case 'phl': $query->orderBy('price', 'desc'); break;
            default:    $query->latest(); break;
        }

        $products = $query->take($this->perPage + 1)->get(); // +1 to check if more exist

        // Check if all products are loaded
        if ($products->count() <= $this->perPage) {
            $this->allLoaded = true;
        }

        return view('livewire.pages.product.product-index-page', [
            'products' => $products->take($this->perPage),
            'categories' => $this->categories,
        ])->layout(Frontend::class);
    }
}
