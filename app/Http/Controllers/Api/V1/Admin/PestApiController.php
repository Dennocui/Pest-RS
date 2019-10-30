<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePestRequest;
use App\Http\Requests\UpdatePestRequest;
use App\Http\Resources\Admin\PestResource;
use App\Pest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PestApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('pest_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PestResource(Pest::with(['category'])->get());
    }

    public function store(StorePestRequest $request)
    {
        $pest = Pest::create($request->all());

        if ($request->input('pest_photo', false)) {
            $pest->addMedia(storage_path('tmp/uploads/' . $request->input('pest_photo')))->toMediaCollection('pest_photo');
        }

        return (new PestResource($pest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Pest $pest)
    {
        abort_if(Gate::denies('pest_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PestResource($pest->load(['category']));
    }

    public function update(UpdatePestRequest $request, Pest $pest)
    {
        $pest->update($request->all());

        if ($request->input('pest_photo', false)) {
            if (!$pest->pest_photo || $request->input('pest_photo') !== $pest->pest_photo->file_name) {
                $pest->addMedia(storage_path('tmp/uploads/' . $request->input('pest_photo')))->toMediaCollection('pest_photo');
            }
        } elseif ($pest->pest_photo) {
            $pest->pest_photo->delete();
        }

        return (new PestResource($pest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Pest $pest)
    {
        abort_if(Gate::denies('pest_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pest->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
