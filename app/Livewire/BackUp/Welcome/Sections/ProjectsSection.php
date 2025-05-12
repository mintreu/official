<?php

namespace App\Livewire\Welcome\Sections;

use App\Models\Project\Project;
use Livewire\Component;

class ProjectsSection extends Component
{
    public function render()
    {
        $allProjects = Project::with([
            'media' => fn($query) => $query->where('collection_name','displayImage')
        ])->where('status',true)->get();

        return view('livewire.welcome.sections.projects-section',[
            'records' => $allProjects
        ]);
    }
}
