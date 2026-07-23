<?php

namespace Modules\Page\Actions;

use Modules\Page\Models\Page;

class MarkPageAsFrontPageAction
{
    public function handle(Page $page)
    {
        Page::where('id', '!=', $page->id)
            ->frontpage()->update([
                'is_frontpage' => false,
            ]);

        if (! $page->is_frontpage) {
            $page->update(['is_frontpage' => true]);
            $page->publish();
        }
    }
}
