<form wire:submit.prevent="submit">

    {{ $this->form }}

    <x-tables::button type="submit" class="mt-2">
        Save
    </x-tables::button>
</form>
