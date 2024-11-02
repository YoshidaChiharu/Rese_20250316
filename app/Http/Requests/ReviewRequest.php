<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['string', 'max:50', 'nullable'],
            'comment' => ['string','max:2000', 'nullable'],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '星を選択して評価してください',
            'rating.between' => '星を選択して評価してください',
        ];
    }
}
