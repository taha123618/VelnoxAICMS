<?php

namespace Modules\Page\Actions;

use Modules\Page\Models\Page;
use Modules\Page\Http\Requests\UpdatePostContentRequest;

class UpdatePostContentAction
{
    public function handle(UpdatePostContentRequest $request, Page $post)
    {

        return tap($post)->update([
            'content' => $request->content
        ]);
    }
}
