<?php

namespace Modules\Page\Actions;

use Modules\Page\Http\Requests\UpdatePageRequest;
use Modules\Page\Models\Page;

class UpdatePageMetadataAction
{
    public function handle(UpdatePageRequest $request, Page $page)
    {
        return tap($page)->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'layout_id' => $request->layout,
            'description' => $request->description,
            'data->keywords' => $request->keywords,
        ]);
    }
}
