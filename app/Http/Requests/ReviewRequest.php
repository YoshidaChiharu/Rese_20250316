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
            'comment' => ['required', 'string', 'max:400'],
            'images.*' => [
                'extensions:jpeg,jpg,png',
                'mimes:jpeg,jpg,png',
                'max:10240'
            ],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '星を選択して評価してください。',
            'rating.between' => '星を選択して評価してください。',
            'images.*.mimes' => 'jpeg, jpg, png形式のファイルを指定してください。',
            'images.*.extensions' => '拡張子が「.jpeg」「.jpg」「.png」何れかのファイルを指定して下さい。',
        ];
    }
}
