<?php

namespace App\Http\Requests;

use App\Models\SmReading;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySmReadingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('sm_reading_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:sm_readings,id',
        ];
    }
}
