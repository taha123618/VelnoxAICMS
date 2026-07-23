<?php

namespace Modules\Page\Actions;

use Modules\Page\Http\Requests\UpdatePostRequest;
use Modules\Page\Models\Page;

class UpdatePostMetadataAction
{
    public function handle(UpdatePostRequest $request, Page $post)
    {
        return tap($post)->update([
            'title' => $request->title,
            'category_id' => $request->category,
            'featured_image' => $request->featuredImage,
            'layout_id' => $request->layout,
            'slug' => $request->slug,
            'excerpt' => $request->excerpt,
            'description' => $request->description,
            'data->keywords' => $request->keywords,
        ]);
    }
}
