<?php

return [
    'group' => 'Settings',

    'label' => 'Settings',

    'path' => storage_path('app/settings.json'),

    'fields' => [
        \Filament\Forms\Components\TextInput::make('title')
    ]
];
