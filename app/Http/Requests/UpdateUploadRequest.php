<?php

namespace App\Http\Requests;

use App\Upload;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateUploadRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('upload_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'description' => [
                'required',
            ],
        ];
    }
}
