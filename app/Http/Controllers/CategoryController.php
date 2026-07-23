<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Modules\Category\Data\CategoryData;
use Modules\Category\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {

        return Inertia::render('category', [
            'category' => CategoryData::fromModel($category),
        ]);

    }
}
