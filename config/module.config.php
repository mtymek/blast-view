<?php

use Blast\View\Factory\ViewFactory;
use Blast\View\View;

return [
    'service_manager' => [
        'factories' => [
            View::class => ViewFactory::class,
        ],
    ],
];
