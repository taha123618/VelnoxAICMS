<?php

namespace Modules\Page\Actions;

use Modules\Page\Enums\PageType;
use Modules\Page\Http\Requests\CreatePostRequest;
use Modules\Page\Models\Page;

class CreatePostAction
{
    public function handle(CreatePostRequest $createPostRequest)
    {
        return Page::create([
            'title' => $createPostRequest->title,
            'category_id' => $createPostRequest->category,
            'layout_id' => $createPostRequest->layout,
            'content' => $createPostRequest->content,
            'type' => PageType::Post,
        ]);
    }
}
