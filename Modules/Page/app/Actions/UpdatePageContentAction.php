<?php

namespace Modules\Page\Actions;

use Modules\Page\Http\Requests\UpdatePageContentRequest;
use Modules\Page\Models\Page;

class UpdatePageContentAction
{
    public function handle(UpdatePageContentRequest $request, Page $page)
    {
        $page->update([
            'content' => $request->content,
        ]);
    }
}
