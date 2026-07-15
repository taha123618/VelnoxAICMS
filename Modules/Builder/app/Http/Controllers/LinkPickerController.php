<?php

namespace Modules\Builder\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Page\Models\Page;
use App\Http\Controllers\Controller;

class LinkPickerController extends Controller
{

    public function __invoke(Request $request)
    {
        return Inertia::render('Builder::link-picker', [
            'pages' => $this->getPageLinks(),
            'posts' => $this->getPostLinks(),
            'payload' => [
                'linkType' => $request->linkType,
                'href' => $request->href
            ]
        ]);
    }

    private function getPageLinks()
    {
        $pages = Page::query()
            ->pages()
            ->orderBy('title')
            ->get();

        return $pages->map(fn(Page $page) => ([
            'id' => $page->id,
            'label' => $page->title,
            'value' => $page->getUrl(false)
        ]));
    }

    private function getPostLinks()
    {
        $posts = Page::query()
            ->posts()
            ->orderBy('title')
            ->get();

        return $posts->map(fn(Page $post) => ([
            'id' => $post->id,
            'label' => $post->title,
            'value' => $post->getUrl(false)
        ]));
    }

}
