<?php

namespace Modules\Media\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Media\Http\Requests\CreateFolderRequest;

class CreateFolderAction
{
    public function handle(CreateFolderRequest $request)
    {

        return Auth::user()->folders()->create([
            'name' => $request->name,
            'parent_id' => $request->parent ?? null
        ]);
        
    }
}
