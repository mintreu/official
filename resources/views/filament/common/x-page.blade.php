<x-filament-panels::page :class="$this->getFilamentPageClass()">
    <div class="p-1">

        @if($this->getPageType() == 'show_info' && $this->hasInfolist() && $this->getRecord())
            {{ $this->infolist }}
        @endif


        @if($this->getPageType() == 'show_form' || $this->getPageType() == 'show_both')

                @if($this->getPageType() == 'show_both' && $this->hasInfolist() && $this->getRecord())
                    <div class="py-2 mb-2">
                        {{ $this->infolist }}
                    </div>
                @endif


            <x-filament-panels::form
                id="form"
                :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
                wire:submit="submit"
            >
                {{ $this->form }}
                <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
                />
            </x-filament-panels::form>

            <x-filament-panels::page.unsaved-data-changes-alert />

        @endif

    </div>


</x-filament-panels::page>
