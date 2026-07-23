<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Polling interval
    |--------------------------------------------------------------------------
    |
    | Default Livewire polling interval applied to every widget. Set to null to
    | disable polling globally. Each widget can override this value through its
    | own "pollingInterval" configuration. Accepts any value supported by the
    | Filament / Livewire "wire:poll" directive, such as "10s" or "30s".
    |
    */

    'polling_interval' => null,

    /*
    |--------------------------------------------------------------------------
    | Empty state defaults
    |--------------------------------------------------------------------------
    |
    | Fallback empty state used by every widget when no data is available and
    | no per widget empty state has been defined. A null heading or description
    | falls back to the translated defaults shipped with the package.
    |
    */

    'empty_state' => [
        'heading' => null,
        'description' => null,
        'icon' => 'heroicon-o-circle-stack',
    ],

    /*
    |--------------------------------------------------------------------------
    | Metric widget
    |--------------------------------------------------------------------------
    */

    'metric' => [
        'show_trend_icon' => true,
        'sparkline_height' => 40,
        'sparkline_max_points' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Goal progress widget
    |--------------------------------------------------------------------------
    */

    'goal' => [
        'cap_progress_at_100' => true,
        'show_percentage' => true,
        'show_remaining' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Breakdown widget
    |--------------------------------------------------------------------------
    */

    'breakdown' => [
        'show_bars' => true,
        'limit' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Trend widget
    |--------------------------------------------------------------------------
    */

    'trend' => [
        'max_points' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Recent items widget
    |--------------------------------------------------------------------------
    */

    'recent_items' => [
        'limit' => 5,
    ],

];
