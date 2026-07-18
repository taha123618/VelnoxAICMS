<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                Rule::unique('pages', 'title')
                    ->ignoreModel($this->post),
            ],
            'slug' => [
                'required',
                'regex:/^[a-zA-Z0-9\-]+$/',
                Rule::unique('pages', 'slug')->ignoreModel($this->post),
            ],
            'category' => ['required', 'exists:categories,id'],
            'layout' => ['required', 'exists:layouts,id'],
            'excerpt' => ['required', 'string', 'max:255'],
            'description' => ['nullable'],
            'featuredImage' => ['nullable'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'May only contain letters, numbers and dash',
        ];
    }
}
