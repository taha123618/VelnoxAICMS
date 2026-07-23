<?php

use Modules\Visits\Visitor;

if (! function_exists('visitor')) {
    /**
     * Access visitor through helper.
     *
     * @return \Modules\Visits\Visitor
     */
    function visitor()
    {
        return app('ziora-visitor');
    }
}
