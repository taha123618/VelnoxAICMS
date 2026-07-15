<?php

namespace Modules\Page\Actions;

use Modules\Page\Models\Page;
use Modules\Page\Http\Requests\UpdatePageContentRequest;

class UpdatePageContentAction
{
    public function handle(UpdatePageContentRequest $request, Page $page)
    {
        $page->update([
            'content' => $request->content
        ]);
    }
}
