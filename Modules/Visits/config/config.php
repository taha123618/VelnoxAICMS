<?php

return [
    'name' => 'Visits',
    'default' => 'jenssegers',
    'except' => ['admin.login', 'admin.register'],
    'wait_minutes' => 5,
    'drivers' => [
        'jenssegers' => \Modules\Visits\Drivers\JenssegersAgent::class,
        'UAParser' => \Modules\Visits\Drivers\UAParser::class,
    ],
];
