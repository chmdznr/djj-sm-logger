<?php

namespace App\Http\Requests;

use App\Models\IotReading;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateIotReadingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('iot_reading_edit');
    }

    public function rules()
    {
        return [
            'responden_id' => [
                'required',
                'integer',
            ],
            'fetal_hr' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'resp_count' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
