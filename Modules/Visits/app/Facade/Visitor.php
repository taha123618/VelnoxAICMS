<?php

declare(strict_types=1);

namespace Modules\Visits\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class viewer
 */
class Visitor extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'VelnoxAI-visitor';
    }
}
