<?php

namespace Reworck\FilamentSettings\Pages;

use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament-settings::pages.settings';

    protected static function getNavigationGroup(): ?string
    {
        return config('filament-settings.group');
    }

    protected static function getNavigationLabel(): string
    {
        return config('filament-settings.label');
    }
}
