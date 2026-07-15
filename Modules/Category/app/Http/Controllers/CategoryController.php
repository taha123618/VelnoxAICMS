<?php

namespace Modules\Category\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Category\Models\Category;
use Modules\Category\Data\CategoryData;
use Illuminate\Support\Facades\Redirect;
use Modules\Category\Actions\CreateCategoryAction;
use Modules\Category\Actions\DeleteCategoryAction;
use Modules\Category\Actions\GetCategoryDropdownOptionsAction;
use Modules\Category\Actions\UpdateCategoryAction;
use Modules\Category\Actions\SearchCategoriesAction;
use Modules\Category\Http\Requests\CreateCategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = app(SearchCategoriesAction::class)->handle($request);

        return Inertia::render('Category::index', [
            'data' => CategoryData::collect($data),
            'filters' => $filters
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Category::class);

        return Inertia::render('Category::create');
    }

    public function store(CreateCategoryRequest $request)
    {
        Gate::authorize('create', Category::class);

        app(CreateCategoryAction::class)->handle($request);

        return Redirect::back()->with('success', 'Category created!');
    }

    public function edit(Category $category)
    {
        Gate::authorize('update', $category);

        $categories = Category::where('id', '!=', $category->id)->get();

        return Inertia::render('Category::edit', [
            'category' => CategoryData::fromModel($category),
            'categories' => CategoryData::collect($categories)
        ]);
    }


    public function update(UpdateCategoryRequest $request, Category $category)
    {
        Gate::authorize('update', $category);

        app(UpdateCategoryAction::class)->handle($request, $category);

        return Redirect::back()->with('success', 'Category updated!');
    }

    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);

        app(DeleteCategoryAction::class)->handle($category);

        return Redirect::back()->with('success', 'Category deleted!');
    }
}
