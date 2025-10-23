<?php

namespace App\Livewire\Backup\Welcome\Pages;

use App\Models\Product\Product;
use App\Models\Subscription\Plan;
use App\View\Components\Layout\AppLayout;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ProductViewPage extends Component
{

    public ?Model $record;
    public $images = [
        ['src' => 'https://via.placeholder.com/600x400?text=Image+1', 'alt' => 'Image 1'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+2', 'alt' => 'Image 2'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+3', 'alt' => 'Image 3'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+4', 'alt' => 'Image 4'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+5', 'alt' => 'Image 5'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+6', 'alt' => 'Image 6'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+7', 'alt' => 'Image 7'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+8', 'alt' => 'Image 8'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+9', 'alt' => 'Image 9'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+10', 'alt' => 'Image 10'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+11', 'alt' => 'Image 11'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+12', 'alt' => 'Image 12'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+13', 'alt' => 'Image 13'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+14', 'alt' => 'Image 14'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+15', 'alt' => 'Image 15'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+16', 'alt' => 'Image 16'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+17', 'alt' => 'Image 17'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+18', 'alt' => 'Image 18'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+19', 'alt' => 'Image 19'],
        ['src' => 'https://via.placeholder.com/600x400?text=Image+20', 'alt' => 'Image 20'],
    ];




    public function mount(Product $product)
    {
        $this->record = $product;
        $this->record->load([
            'media' => fn($query) => $query->where('collection_name','displayImage'),
            'project' => fn($query) => $query
                ->with([
                    'products' => fn($query) => $query->with([
                        'media' => fn($query) => $query->where('collection_name','displayImage'),
                    ])->where('status',true)->limit(6)
                ]),
        ]);
    }



    public function startProject(string $url)
    {
//        $this->redirect(StartProjectPage::getUrl().'?product='.$this->record->url.'&plan='.$id);
        $this->redirect(route('filament.app.pages.new-project').'?product='.$this->record->url.'&plan='.$url);
    }



    public function render()
    {


        return view('livewire.welcome.pages.product-view-page',[
            'plans' => Plan::where('visible_on_front',true)->where('is_enterprise',false)->get(),
            'top4Products' => Product::with([
                'media' => fn($query) => $query->where('collection_name','displayImage'),
            ])->where('project_id','!=',$this->record->project->id)
                ->limit(4)
                ->where('status',true)
                ->get()
        ])->layout(AppLayout::class);
    }
}
