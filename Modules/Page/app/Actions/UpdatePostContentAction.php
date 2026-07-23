<?php

namespace Modules\Page\Actions;

use Modules\Page\Http\Requests\UpdatePostContentRequest;
use Modules\Page\Models\Page;

class UpdatePostContentAction
{
    public function handle(UpdatePostContentRequest $request, Page $post)
    {

        return tap($post)->update([
            'content' => $request->content,
        ]);
    }
}
