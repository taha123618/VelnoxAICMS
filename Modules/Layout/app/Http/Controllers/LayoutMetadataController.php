<?php

namespace Modules\Layout\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Layout\Actions\UpdateLayoutMetadataAction;
use Modules\Layout\Data\LayoutData;
use Modules\Layout\Http\Requests\UpdateLayoutMetadataRequest;
use Modules\Layout\Models\Layout;

class LayoutMetadataController extends Controller
{
    public function edit(Layout $layout)
    {
        Gate::authorize('update', $layout);

        return Inertia::render('Layout::edit-metadata', [
            'layout' => LayoutData::fromModel($layout),
        ]);
    }

    public function update(UpdateLayoutMetadataRequest $request, Layout $layout)
    {

        Gate::authorize('update', $layout);

        app(UpdateLayoutMetadataAction::class)->handle($request, $layout);

        return Redirect::back()->with('success', 'Category updated!');
    }
}
