<?php

namespace App\Http\Requests;

use App\Models\Responden;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyRespondenRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('responden_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:respondens,id',
        ];
    }
}
