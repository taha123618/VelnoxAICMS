<?php

namespace Modules\Visits\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class viewer
 */
class Visitor extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'ziora-visitor';
    }
}
