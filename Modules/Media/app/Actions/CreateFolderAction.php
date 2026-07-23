<?php

namespace Modules\Media\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Media\Http\Requests\CreateFolderRequest;

class CreateFolderAction
{
    public function handle(CreateFolderRequest $createFolderRequest)
    {

        return Auth::user()->folders()->create([
            'name' => $createFolderRequest->name,
            'parent_id' => $createFolderRequest->parent ?? null,
        ]);

    }
}
