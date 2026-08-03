<?php

declare(strict_types=1);

use Modules\Visits\Visitor;

if (! function_exists('visitor')) {
    /**
     * Access visitor through helper.
     *
     * @return Visitor
     */
    function visitor()
    {
        return resolve('VelnoxAI-visitor');
    }
}
