<?php

namespace Modules\Page\Actions;

use Modules\Page\Http\Requests\UpdatePostContentRequest;
use Modules\Page\Models\Page;

class UpdatePostContentAction
{
    public function handle(UpdatePostContentRequest $updatePostContentRequest, Page $page)
    {

        return tap($page)->update([
            'content' => $updatePostContentRequest->content,
        ]);
    }
}
