<?php

namespace Modules\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Modules\Media\Actions\CreateFolderAction;
use Modules\Media\Actions\DeleteFolderAction;
use Modules\Media\Actions\GetFolderBreadcrumbsAction;
use Modules\Media\Actions\GetFolderContentAction;
use Modules\Media\Actions\GetFolderTreeAction;
use Modules\Media\Actions\UpdateFolderAction;
use Modules\Media\Data\FolderData;
use Modules\Media\Http\Requests\CreateFolderRequest;
use Modules\Media\Http\Requests\UpdateFolderRequest;
use Modules\Media\Models\Folder;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $mode = $request->mode ?? 'single';

        $folderId = $request->folder ?? Session::get('lastVisitedFolderId');

        $folder = Folder::find($folderId) ?? Folder::getOrCreateRoot();

        Session::put('lastVisitedFolderId', $folder->id);

        $breadcrumbs = resolve(GetFolderBreadcrumbsAction::class)->handle($folder);

        $content = resolve(GetFolderContentAction::class)->handle($folder);

        $folderTree = resolve(GetFolderTreeAction::class)->handle();

        return Inertia::render('Media::folders/index', [
            'isModalPage' => $request->hasHeader('x-inertiaui-modal'),
            'mode' => $mode,
            'folders' => $folderTree,
            'content' => $content,
            'breadcrumbs' => $breadcrumbs,
            'currentFolder' => $folder->id,
        ]);
    }

    public function create(Request $request)
    {
        Gate::authorize('create', Folder::class);

        return Inertia::render('Media::folders/create', [
            'parentFolder' => $request->parent ?? null,
        ]);
    }

    public function store(CreateFolderRequest $createFolderRequest)
    {
        Gate::authorize('create', Folder::class);

        resolve(CreateFolderAction::class)->handle($createFolderRequest);

        return back()->with('success', 'Folder created!');
    }

    public function edit(Folder $folder)
    {
        Gate::authorize('update', $folder);

        return Inertia::render('Media::folders/edit', [
            'folder' => FolderData::fromModel($folder),
        ]);
    }

    public function update(UpdateFolderRequest $updateFolderRequest, Folder $folder)
    {
        Gate::authorize('update', $folder);

        resolve(UpdateFolderAction::class)->handle($updateFolderRequest, $folder);

        return back()->with('success', 'Folder updated!');
    }

    public function destroy(Folder $folder)
    {
        Gate::authorize('delete', $folder);

        resolve(DeleteFolderAction::class)->handle($folder);

        return back()->with('success', 'Folder deleted!');
    }
}
