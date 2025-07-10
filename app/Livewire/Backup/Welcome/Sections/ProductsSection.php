<?php

namespace App\Livewire\Backup\Welcome\Sections;

use App\Models\Category\Category;
use App\Models\Product\Product;
use Livewire\Component;
use Livewire\WithPagination;


class ProductsSection extends Component
{
    use WithPagination;

    public $selectedCategory = '';
    public $selectedProductType = '';
    protected $paginationTheme = 'tailwind';

    public function render()
    {
        // Retrieve categories
        $allCategories = Category::where('status', true)->get();

        // Build the product query with filters
        $query = Product::with([
            'media' => fn($query) => $query->where('collection_name', 'displayImage')
        ])->where('status', true);

        // Filter by category if selected
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        // Filter by product type if selected
        if ($this->selectedProductType) {
            $query->where('type', $this->selectedProductType);
        }

        // Paginate the results to display 12 products per page
        $allProducts = $query->paginate(12);

        return view('livewire.welcome.sections.products-section', [
            'records' => $allProducts,
            'categories' => $allCategories,
        ]);
    }

    // Reset pagination when filtering
    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSelectedProductType()
    {
        $this->resetPage();
    }
}
