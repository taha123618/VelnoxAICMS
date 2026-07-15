<?php

namespace Modules\Media\Actions;

use Modules\Media\Models\Media;

class DeleteMediaAction
{
    public function handle(Media $file)
    {
        $file->delete();
    }
}
