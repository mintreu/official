<?php

namespace App\Filament\Common\Pages\XPage;

use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Js;

abstract class XPage extends Page
{

    use InteractsWithInfolists, InteractsWithFormActions,HasUnsavedDataChangesAlert;
    use InteractsWithForms;

    protected static string $view = 'filament.common.x-page';

    protected const SHOW_INFO = 'show_info';
    protected const SHOW_BOTH = 'show_both';
    protected const SHOW_FORM = 'show_form';

    protected static bool $createRecord = false;

    public ?Model $record = null;
    public ?array $data = [];



    public function mount()
    {
        if (!is_null($this->record))
        {
            $this->form->fill($this->record->toArray());
        }else{
            $this->form->fill([]);
        }

    }





    protected function getRecord()
    {
        return $this->record;
    }


    public function getTitle(): string|Htmlable
    {
        return self::$title ?? config('app.name');
    }


    public function hasLogo():bool
    {
        return false;
    }



// Method to get the page type
    protected function getPageType(): string
    {
        return self::SHOW_INFO;
    }

    protected function hasRecord():bool
    {
        return false;
    }


    protected function getFilamentPageClass(): string
    {
        $attributes = [];

        if ($this->getPageType() != self::SHOW_INFO)
        {
            if (self::$createRecord)
            {
                $attributes [] = 'fi-resource-create-record-page';
            }

            if (!self::$createRecord)
            {
                $attributes [] =  'fi-resource-edit-record-page';
            }
        }else{
            $attributes [] =  'fi-resource-view-record-page';
        }


        if (isset(self::$resource))
        {
            $attributes [] = 'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug());
        }
        if (!is_null($this->record))
        {
            $attributes [] = 'fi-resource-record-' . $this->record->getKey();
        }



        return implode(',',$attributes);
    }





    protected function hasInfolist(): bool
    {
        return (bool) count($this->getInfolist('infolist')->getComponents());
    }





    public function form(Form $form): Form
    {
        $operation = self::$createRecord ? 'create' : 'edit';
        $operation = $this->getPageType() == self::SHOW_BOTH ? 'view' : $operation;
        return $form
            ->operation($operation)
            ->model($this->record)
            ->statePath('data')
            ->columns($this->hasInlineLabels() ? 1 : 2)
            ->inlineLabel($this->hasInlineLabels());
    }




    public function getFormStatePath(): ?string
    {
        return 'data';
    }


//    protected function makeInfolist(): Infolist
//    {
//        return parent::makeInfolist()
//            ->record($this->getRecord())
//            ->columns($this->hasInlineLabels() ? 1 : 2)
//            ->inlineLabel($this->hasInlineLabels());
//    }

    protected function infolist(Infolist $infolist): Infolist
    {
        return $infolist;
    }

    protected function makeInfolist(): Infolist
    {
        return parent::makeInfolist()
            ->record($this->getRecord())
            ->columns($this->hasInlineLabels() ? 1 : 2)
            ->inlineLabel($this->hasInlineLabels());
    }


    protected function getCreateFormAction(): Action
    {
        return Action::make('submit')
            ->label(fn() => self::$createRecord ? __('Create') : __('Save'))
            ->submit('submit')
            ->keyBindings(['mod+s']);
    }

    protected function getCachedFormActions(): array
    {
        return $this->getPageType() == self::SHOW_INFO ? [] :[
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.cancel.label'))
            ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from($this->previousUrl ?? static::getUrl()) . ')')
            ->color('gray');
    }




}
