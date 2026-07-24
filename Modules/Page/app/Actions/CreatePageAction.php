<?php

namespace Modules\Page\Actions;

use Modules\Page\Enums\PageType;
use Modules\Page\Http\Requests\CreatePageRequest;
use Modules\Page\Models\Page;

class CreatePageAction
{
    public function handle(CreatePageRequest $createPageRequest)
    {

        return Page::create([
            'title' => $createPageRequest->title,
            'layout_id' => $createPageRequest->layout,
            'content' => $createPageRequest->content,
            'type' => PageType::Page,
        ]);
    }
}
