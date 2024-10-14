<x-filament-panels::page>
    <div class="px-2">
        @if ($this->hasInfolist() && !$this->hasFormWithInfolist())
            {{ $this->infolist }}
        @else

            @if($this->hasFormWithInfolist())

                <div class="my-2">
                    {{ $this->infolist }}
                </div>

{{--                <div--}}
{{--                    wire:key="{{ $this->getId() }}.forms.{{ $this->getFormStatePath() }}"--}}
{{--                >--}}
{{--                    {{ $this->form }}--}}
{{--                </div>--}}



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


            @else
                <div
                    wire:key="{{ $this->getId() }}.forms.{{ $this->getFormStatePath() }}"
                >
                    {{ $this->form }}
                </div>
            @endif



        @endif
    </div>


</x-filament-panels::page>
