<?php

declare(strict_types=1);

namespace Modules\Page\Actions;

use Modules\Page\Http\Requests\UpdatePageContentRequest;
use Modules\Page\Models\Page;

class UpdatePageContentAction
{
    public function handle(UpdatePageContentRequest $updatePageContentRequest, Page $page): void
    {
        $page->update([
            'content' => $updatePageContentRequest->content,
        ]);
    }
}
