<?php

namespace Modules\Layout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLayoutRequest extends FormRequest
{
   
    public function rules(): array
    {
        return [
            'content' => ['required']
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
