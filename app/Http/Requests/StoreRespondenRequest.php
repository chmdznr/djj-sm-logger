<?php

namespace App\Http\Requests;

use App\Models\Responden;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreRespondenRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('responden_create');
    }

    public function rules()
    {
        return [
            'nama' => [
                'string',
                'required',
            ],
            'kode' => [
                'string',
                'required',
            ],
            'usia' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'paritas' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'kardiotokografi' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
