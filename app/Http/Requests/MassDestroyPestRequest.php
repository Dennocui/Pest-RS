<?php

namespace App\Http\Requests;

use App\Pest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('pest_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:pests,id',
        ];
    }
}
