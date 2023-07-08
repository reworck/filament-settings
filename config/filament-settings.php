<?php

return [
    // Group the menu item belongs to
    'group' => 'Settings',

    // Sidebar label
    'label' => 'Settings',

    // Page title
    'title' => 'Settings',

    // Replace with a custom page to have more control over appearance etc. 
    'page' => \Reworck\FilamentSettings\Pages\Settings::class,

    // Path to the file to be used as storage
    'path' => storage_path('app/settings.json'),
];
