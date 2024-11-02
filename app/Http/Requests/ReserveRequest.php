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
            'reserve_prepayment' => ['required'],
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

                // 事前決済時はコース選択必須
                if ($this->input('reserve_prepayment') == 1) {
                    if ($this->input('reserve_course_id') == null) {
                        $validator->errors()->add(
                            'reserve_course_id',
                            '事前決済を行う場合はコースを選択してください'
                        );
                    }
                }
            }
        ];
    }
}
