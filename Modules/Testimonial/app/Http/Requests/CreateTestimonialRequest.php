<?php

namespace Modules\Testimonial\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTestimonialRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable'],
            'designation' => ['nullable'],
            'comment' => ['required']
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
