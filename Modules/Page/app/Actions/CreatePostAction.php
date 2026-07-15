<?php

namespace Modules\Page\Actions;

use Modules\Page\Models\Page;
use Modules\Page\Enums\PageType;
use Modules\Page\Http\Requests\CreatePostRequest;

class CreatePostAction
{
    public function handle(CreatePostRequest $request)
    {
        return Page::create([
            'title' => $request->title,
            'category_id' => $request->category,
            'layout_id' => $request->layout,
            'content' => $request->content,
            'type' => PageType::Post
        ]);
    }
}
