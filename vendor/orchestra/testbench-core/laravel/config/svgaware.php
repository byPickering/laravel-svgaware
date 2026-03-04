<?php 

return [
    'root'       => env('SVGAWARE_ROOT', resource_path('svg')),
    'component'  => env('SVGAWARE_COMPONENT', 'svg'),
    'directive'  => env('SVGAWARE_DIRECTIVE', 'svg'),
    'append'     => env('SVGAWARE_APPEND', '.svg'),
    'prepend'    => env('SVGAWARE_PREPEND', ''),
    'purge'      => env('SVGAWARE_PURGE', true),
    'purge_list' => ['width', 'height', 'fill'],
];