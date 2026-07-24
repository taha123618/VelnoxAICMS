<?php

namespace Modules\Page\Actions;

use Modules\Page\Http\Requests\UpdatePageRequest;
use Modules\Page\Models\Page;

class UpdatePageMetadataAction
{
    public function handle(UpdatePageRequest $updatePageRequest, Page $page)
    {
        return tap($page)->update([
            'title' => $updatePageRequest->title,
            'slug' => $updatePageRequest->slug,
            'layout_id' => $updatePageRequest->layout,
            'description' => $updatePageRequest->description,
            'data->keywords' => $updatePageRequest->keywords,
        ]);
    }
}
