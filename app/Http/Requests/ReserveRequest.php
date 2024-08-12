<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Validation\Validator;

class ReserveRequest extends FormRequest
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
            'reserve_date' => ['required', 'date', 'after_or_equal:today'],
            'reserve_time' => ['required', 'date_format:H:i'],
            'reserve_number' => ['required', 'between:1,10'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $now = CarbonImmutable::now();
                $today = $now->format('Y-m-d');
                $time_limit = $now->addHour(3);
                $reserve_time = new Carbon($this->input('reserve_time'));

                // 当日予約は来店時刻の「3時間前」までしか受け付けない
                if ($this->input('reserve_date') === $today) {
                    if ($reserve_time < $time_limit) {
                        $validator->errors()->add(
                            'reserve_time',
                            '当日予約は来店時刻の3時間前までしか行えません'
                        );
                    }
                }
            }
        ];
    }
}
