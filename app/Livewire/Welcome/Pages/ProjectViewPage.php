<?php

namespace App\Livewire\Welcome\Pages;

use App\Models\Project\Project;
use App\View\Components\Layout\AppLayout;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ProjectViewPage extends Component
{
    public ?Model $record;
    public string $filterType = ''; // Add a property to store the selected type

    public function mount(Project $project)
    {
        $this->record = $project;
        $this->record->load([
            'media' => fn($query) => $query->where('collection_name', 'displayImage'),
            'products' => fn($query) => $query->with([
                'media' => fn($query) => $query->where('collection_name', 'displayImage')
            ])->where('status', true)
        ]);
    }

    // This method is triggered when the select option changes
    public function updatedFilterType()
    {
       // dd($this);
        $this->record->load([
            'media' => fn($query) => $query->where('collection_name', 'displayImage'),
            'products' => fn($query) => $query->with([
                'media' => fn($query) => $query->where('collection_name', 'displayImage')
            ])->where('status', true)
                ->when($this->filterType, fn($query) => $query->where('type', $this->filterType)) // Add filtering logic
        ]);
    }

    public function updateFilterType()
    {

    }

    public function render()
    {


        return view('livewire.welcome.pages.project-view-page')->layout(AppLayout::class);
    }
}
