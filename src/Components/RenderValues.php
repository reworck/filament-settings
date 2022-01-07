<?php

namespace Reworck\FilamentSettings\Components;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Spatie\Valuestore\Valuestore;

class RenderValues extends Component implements HasForms
{
    use InteractsWithForms;

    public array $data;

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function filePath(): string
    {
        return config('filament-settings.path');
    }

    protected function getFormSchema(): array
    {
        return config('filament-settings.fields');
    }

    public function mount(): void
    {
        $this->form->fill(
            Valuestore::make(
                $this->filePath()
            )->all()
        );
    }

    public function submit(): void
    {
        foreach ($this->data as $key => $data) {
            Valuestore::make(
                $this->filePath()
            )->put($key, $data);
        }
    }

    public function render(): View
    {
        return view('filament-settings::components.values');
    }
}
