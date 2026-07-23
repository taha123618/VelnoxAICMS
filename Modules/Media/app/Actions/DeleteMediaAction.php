<?php

declare(strict_types=1);

namespace Modules\Media\Actions;

use Modules\Media\Models\Media;

class DeleteMediaAction
{
    public function handle(Media $media): void
    {
        $media->delete();
    }
}
