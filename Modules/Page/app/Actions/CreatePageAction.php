<?php

namespace Modules\Page\Actions;

use Modules\Page\Enums\PageType;
use Modules\Page\Http\Requests\CreatePageRequest;
use Modules\Page\Models\Page;

class CreatePageAction
{
    public function handle(CreatePageRequest $request)
    {

        return Page::create([
            'title' => $request->title,
            'layout_id' => $request->layout,
            'content' => $request->content,
            'type' => PageType::Page,
        ]);
    }
}
