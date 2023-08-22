<?php

namespace App\Http\Requests;

use App\Models\SmReading;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSmReadingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sm_reading_edit');
    }

    public function rules()
    {
        return [
            'responden_id' => [
                'required',
                'integer',
            ],
            'spo_2' => [
                'numeric',
                'required',
            ],
            'hr' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'skin_temp' => [
                'numeric',
                'required',
            ],
        ];
    }
}
