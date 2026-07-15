<?php

namespace Modules\Page\Actions;

use Modules\Page\Enums\PageType;
use Modules\Page\Models\Page;

class GetAllPagesWithUnpublishedChangesAction
{
    public function handle(PageType $type)
    {
        $query = Page::query();

        if ($type == PageType::Page) {
            $query->pages();
        }

        if ($type == PageType::Post) {
            $query->posts();
        }

        return $query->whereHas('published_version', function ($query) {
            $query->whereColumn('pages.content', '!=', 'published_pages.content')
                ->orWhereColumn('pages.title', '!=', 'published_pages.title')
                ->orWhereColumn('pages.description', '!=', 'published_pages.description')
                ->orWhereColumn('pages.excerpt', '!=', 'published_pages.excerpt')
                ->orWhereColumn('pages.featured_image', '!=', 'published_pages.featured_image')
                ->orWhereColumn('pages.data', '!=', 'published_pages.data');
        })->get();
    }
}
