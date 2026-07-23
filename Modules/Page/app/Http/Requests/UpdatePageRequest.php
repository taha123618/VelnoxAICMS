<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                Rule::unique('pages', 'title')->ignoreModel($this->page),
            ],
            'slug' => [
                'required',
                'regex:/^[a-zA-Z0-9\-]+$/',
                Rule::unique('pages', 'slug')->ignoreModel($this->page),
            ],
            'layout' => ['required', 'exists:layouts,id'],
            'description' => ['nullable'],
            'keywords' => ['required', 'array', 'min:0'],
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
