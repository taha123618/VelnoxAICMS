<?php

namespace Modules\Page\Actions;

use Modules\Page\Models\Page;

class DuplicatePageAction
{
    public function handle(Page $page)
    {
        $baseTitle = $page->title;

        $pattern = '/( copy(?:\d*)?)$/i';

        $baseTitle = preg_replace($pattern, '', $baseTitle);

        $existingTitles = Page::query()->where('title', 'LIKE', "$baseTitle copy%")
            ->pluck('title')
            ->toArray();

        $copyNumber = 1;

        $newTitle = "$baseTitle copy";

        while (in_array($newTitle, $existingTitles)) {
            $copyNumber++;
            $newTitle = "$baseTitle copy{$copyNumber}";
        }

        $newPage = $page->replicate()->fill([
            'is_frontpage' => false,
            'published_at' => null,
            'title' => $newTitle
        ]);

        $newPage->save();

        return $newPage;
    }
}
