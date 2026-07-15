<?php

namespace Modules\Page\Actions;

use Modules\Page\Models\Page;

class DeletePostAction
{
    public function handle(Page $post)
    {
        $post->delete();
    }
}
