<?php

declare(strict_types=1);

namespace Modules\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Media\Actions\UpdateDraggedFolderParentAction;
use Modules\Media\Actions\UpdateDraggedMediaAction;
use Modules\Media\Models\Folder;

class DraggableMediaController extends Controller
{
    public function update(Request $request): RedirectResponse
    {

        resolve(UpdateDraggedMediaAction::class)->handle($request);

        return back();
    }

    public function drag(Request $request, Folder $folder)
    {

        Gate::authorize('update', $folder);

        resolve(UpdateDraggedFolderParentAction::class)->handle($request, $folder);

        return back()->with('success', 'Folder updated!');
    }
}
