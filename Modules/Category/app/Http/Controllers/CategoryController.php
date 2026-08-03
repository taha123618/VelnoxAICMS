<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Modules\Category\Actions\CreateCategoryAction;
use Modules\Category\Actions\DeleteCategoryAction;
use Modules\Category\Actions\SearchCategoriesAction;
use Modules\Category\Actions\UpdateCategoryAction;
use Modules\Category\Data\CategoryData;
use Modules\Category\Http\Requests\CreateCategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = resolve(SearchCategoriesAction::class)->handle($request);

        return Inertia::render('Category::index', [
            'data' => CategoryData::collect($data),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Category::class);

        return Inertia::render('Category::create');
    }

    public function store(CreateCategoryRequest $createCategoryRequest)
    {
        Gate::authorize('create', Category::class);

        resolve(CreateCategoryAction::class)->handle($createCategoryRequest);

        return back()->with('success', 'Category created!');
    }

    public function edit(Category $category)
    {
        Gate::authorize('update', $category);

        $categories = Category::where('id', '!=', $category->id)->get();

        return Inertia::render('Category::edit', [
            'category' => CategoryData::fromModel($category),
            'categories' => CategoryData::collect($categories),
        ]);
    }

    public function update(UpdateCategoryRequest $updateCategoryRequest, Category $category)
    {
        Gate::authorize('update', $category);

        resolve(UpdateCategoryAction::class)->handle($updateCategoryRequest, $category);

        return back()->with('success', 'Category updated!');
    }

    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);

        resolve(DeleteCategoryAction::class)->handle($category);

        return back()->with('success', 'Category deleted!');
    }
}
