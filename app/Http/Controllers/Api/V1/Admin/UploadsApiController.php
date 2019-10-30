<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreUploadRequest;
use App\Http\Requests\UpdateUploadRequest;
use App\Http\Resources\Admin\UploadResource;
use App\Upload;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('upload_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new UploadResource(Upload::with(['created_by'])->get());
    }

    public function store(StoreUploadRequest $request)
    {
        $upload = Upload::create($request->all());

        if ($request->input('pest_image', false)) {
            $upload->addMedia(storage_path('tmp/uploads/' . $request->input('pest_image')))->toMediaCollection('pest_image');
        }

        return (new UploadResource($upload))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Upload $upload)
    {
        abort_if(Gate::denies('upload_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new UploadResource($upload->load(['created_by']));
    }

    public function update(UpdateUploadRequest $request, Upload $upload)
    {
        $upload->update($request->all());

        if ($request->input('pest_image', false)) {
            if (!$upload->pest_image || $request->input('pest_image') !== $upload->pest_image->file_name) {
                $upload->addMedia(storage_path('tmp/uploads/' . $request->input('pest_image')))->toMediaCollection('pest_image');
            }
        } elseif ($upload->pest_image) {
            $upload->pest_image->delete();
        }

        return (new UploadResource($upload))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Upload $upload)
    {
        abort_if(Gate::denies('upload_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $upload->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
