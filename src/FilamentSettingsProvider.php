<?php

namespace Reworck\FilamentSettings;

use Filament\PluginServiceProvider;
use Livewire\Livewire;
use Reworck\FilamentSettings\Components\RenderValues;
use Reworck\FilamentSettings\Pages\Settings;
use Spatie\LaravelPackageTools\Package;

class FilamentSettingsProvider extends PluginServiceProvider
{
    public static string $name = 'filament-settings';

    protected function getPages(): array
    {
        return [
            Settings::class,
        ];
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        Livewire::component(RenderValues::getName(), RenderValues::class);
    }

    public function configurePackage(Package $package): void
    {
        parent::configurePackage($package);

        $package->hasConfigFile('filament-settings');
    }
}
