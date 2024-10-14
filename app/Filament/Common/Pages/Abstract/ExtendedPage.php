<?php

namespace App\Filament\Common\Pages\Abstract;

use App\Models\User;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class ExtendedPage  extends Page
{
    use InteractsWithInfolists, InteractsWithFormActions;

    protected static string $view = 'filament.common.extended-page';
    public ?Model $record = null;


    protected function getRecord()
    {
        return $this->record;
    }

    public function mount()
    {
        $this->record = User::find(1);
    }

    protected function hasInfolist(): bool
    {
        return (bool) count($this->getInfolist('infolist')->getComponents());
    }

    protected function hasFormWithInfolist():bool
    {
        return false;
    }

    protected function makeInfolist(): Infolist
    {
        return parent::makeInfolist()
            ->record($this->getRecord())
            ->columns($this->hasInlineLabels() ? 1 : 2)
            ->inlineLabel($this->hasInlineLabels());
    }


}
