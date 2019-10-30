<?php

namespace App\Http\Requests;

use App\Pest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdatePestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('pest_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'pest_name' => [
                'min:0',
                'max:150',
                'required',
            ],
        ];
    }
}
