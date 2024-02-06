<?php

namespace Reworck\FilamentSettings;

use Spatie\Valuestore\Valuestore;

class FilamentSettings
{
    public static array $fields = [];

    public static function setFormFields(array $fields): void
    {
        self::$fields = $fields;
    }

    public static function value($value, $fallback = null)
    {
        return Valuestore::make(config('filament-settings.path'))->get($value) ?? $fallback;
    }
}
