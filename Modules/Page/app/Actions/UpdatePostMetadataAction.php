<?php

namespace Modules\Page\Actions;

use Modules\Page\Http\Requests\UpdatePostRequest;
use Modules\Page\Models\Page;

class UpdatePostMetadataAction
{
    public function handle(UpdatePostRequest $updatePostRequest, Page $page)
    {
        return tap($page)->update([
            'title' => $updatePostRequest->title,
            'category_id' => $updatePostRequest->category,
            'featured_image' => $updatePostRequest->featuredImage,
            'layout_id' => $updatePostRequest->layout,
            'slug' => $updatePostRequest->slug,
            'excerpt' => $updatePostRequest->excerpt,
            'description' => $updatePostRequest->description,
            'data->keywords' => $updatePostRequest->keywords,
        ]);
    }
}
