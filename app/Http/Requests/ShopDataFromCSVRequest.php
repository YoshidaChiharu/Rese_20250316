<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Validator;

class ShopDataFromCSVRequest extends FormRequest
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
            'csv' => [
                'required',
                'file',
                'extensions:csv',
            ],
            'shop_data.*.owner_id' => ['required'],
            'shop_data.*.name' => ['required', 'max:50'],
            'shop_data.*.area' => ['required', 'in:東京都,大阪府,福岡県'],
            'shop_data.*.genre' => ['required', 'in:寿司,焼肉,イタリアン,居酒屋,ラーメン'],
            'shop_data.*.detail' => ['required', 'max:400'],
            'shop_data.*.image' => ['required'],
            'images' => 'required',
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
            'images.*.mimes' => '画像にはjpeg, jpg, png形式のファイルを指定してください。',
            'images.*.extensions' => '画像には拡張子が「.jpeg」「.jpg」「.png」何れかのファイルを指定して下さい。',
        ];
    }

    protected function prepareForValidation()
    {
        // csvから「店舗情報配列」と「必要な画像ファイル名の配列」を作成しrequestへ追加
        if ($this->file('csv')) {
            $csvData = File::get($this->file('csv'));
            $rows = explode("\n", trim($csvData));

            for ($i = 1; $i < count($rows); $i++) {
                $data = str_getcsv($rows[$i]);

                // 店舗情報配列
                $shop_data[$i] = [
                    'owner_id' => empty($data[0]) ? null : $data[0],
                    'name' => empty($data[1]) ? null : $data[1],
                    'area' => empty($data[2]) ? null : $data[2],
                    'genre' => empty($data[3]) ? null : $data[3],
                    'detail' => empty($data[4]) ? null : $data[4],
                    'image' => empty($data[5]) ? null : "img/".$data[5],
                    'prepayment_enabled' => false,
                ];

                // 必要な画像ファイル名の配列
                if (!empty($data[5])) {
                    $need_image_names[] = $data[5];
                }
            }

            // requestに項目追加
            if (isset($shop_data)) {
                $this->merge(compact('shop_data'));
            }
            if (isset($need_image_names)) {
                $need_image_names = array_unique($need_image_names);
                $this->merge(compact('need_image_names'));
            }
        }

        // 「受け取った画像ファイル名の配列」を作成しrequestに追加
        if ($this->images) {
            foreach ($this->images as $image) {
                $input_image_names[] = $image->getClientOriginalName();
            }
            $this->merge(compact('input_image_names'));
        }
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                // csv内に一つも店舗情報が無い場合
                if (!isset($this->shop_data)) {
                    $validator->errors()->add(
                        'csv',
                        'csvに店舗情報が含まれていません'
                    );
                }

                // 画像ファイルが不足している場合
                if (isset($this->need_image_names) && isset($this->input_image_names)) {
                    $diff_image_names = array_diff($this->need_image_names, $this->input_image_names);

                    if ($diff_image_names) {
                        $validator->errors()->add(
                            'images',
                            '画像ファイルが不足しています'
                        );
                    }
                }
            }
        ];
    }
}
