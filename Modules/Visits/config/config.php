<?php

declare(strict_types=1);
use Modules\Visits\Drivers\JenssegersAgent;
use Modules\Visits\Drivers\UAParser;

return [
    'name' => 'Visits',
    'default' => 'jenssegers',
    'except' => ['admin.login', 'admin.register'],
    'wait_minutes' => 5,
    'drivers' => [
        'jenssegers' => JenssegersAgent::class,
        'UAParser' => UAParser::class,
    ],
];
