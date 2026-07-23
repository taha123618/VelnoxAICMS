<?php

namespace Modules\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Media\Actions\UpdateDraggedFolderParentAction;
use Modules\Media\Actions\UpdateDraggedMediaAction;
use Modules\Media\Models\Folder;

class DraggableMediaController extends Controller
{
    public function update(Request $request)
    {

        app(UpdateDraggedMediaAction::class)->handle($request);

        return Redirect::back();
    }

    public function drag(Request $request, Folder $folder)
    {

        Gate::authorize('update', $folder);

        app(UpdateDraggedFolderParentAction::class)->handle($request, $folder);

        return Redirect::back()->with('success', 'Folder updated!');
    }
}
