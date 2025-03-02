<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Theme Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the default themes for authentication and global
    | usage within the web application. You can set the layout files here
    | according to your design needs.
    |
    */

    'default' => [
        'theme' => [
            'auth' => 'layout.default.default-theme', // Layout for authentication views
            // 'global' => 'layout.default.default-paralax-theme', // Uncomment to use the parallax theme globally
            'global' => 'layout.default.default-standard-theme', // Standard layout for other views
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Themes
    |--------------------------------------------------------------------------
    |
    | List of all available themes for the web application. Each theme
    | can be selected based on user preferences or application state.
    |
    */

    'themes' => [
        'layout.default.default-theme', // Full Screen BG Animation Without Touch Effect
        'layout.default.default-paralax-theme', // Full Screen Parallax Touch Effect On BG
        'layout.default.default-standard-theme', // Showcase In Sections
    ],

    /*
    |--------------------------------------------------------------------------
    | Sidebar Configuration
    |--------------------------------------------------------------------------
    |
    | This section contains settings related to the sidebar's visibility.
    | You can toggle the sidebar's display by changing the 'show' value.
    |
    */

    'sidebar' => [
        'show' => false, // Set to true to display the sidebar, false to hide it
    ],
];
