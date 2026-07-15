<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                // 'unique:pages,title'
            ],
            'category' => ['required', 'exists:categories,id'],
            'layout' => ['required', 'exists:layouts,id'],
            'content' => ['required'],
            'description' => ['nullable'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
