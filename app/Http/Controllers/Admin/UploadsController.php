<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyUploadRequest;
use App\Http\Requests\StoreUploadRequest;
use App\Http\Requests\UpdateUploadRequest;
use App\Upload;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('upload_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $uploads = Upload::all();

        return view('admin.uploads.index', compact('uploads'));
    }

    public function create()
    {
        abort_if(Gate::denies('upload_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.uploads.create');
    }

    public function store(StoreUploadRequest $request)
    {
        $upload = Upload::create($request->all());

        foreach ($request->input('pest_image', []) as $file) {
            $upload->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('pest_image');
        }

        return redirect()->route('admin.uploads.index');
    }

    public function edit(Upload $upload)
    {
        abort_if(Gate::denies('upload_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $upload->load('created_by');

        return view('admin.uploads.edit', compact('upload'));
    }

    public function update(UpdateUploadRequest $request, Upload $upload)
    {
        $upload->update($request->all());

        if (count($upload->pest_image) > 0) {
            foreach ($upload->pest_image as $media) {
                if (!in_array($media->file_name, $request->input('pest_image', []))) {
                    $media->delete();
                }
            }
        }

        $media = $upload->pest_image->pluck('file_name')->toArray();

        foreach ($request->input('pest_image', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $upload->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('pest_image');
            }
        }

        return redirect()->route('admin.uploads.index');
    }

    public function show(Upload $upload)
    {
        abort_if(Gate::denies('upload_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $upload->load('created_by');

        return view('admin.uploads.show', compact('upload'));
    }

    public function destroy(Upload $upload)
    {
        abort_if(Gate::denies('upload_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $upload->delete();

        return back();
    }

    public function massDestroy(MassDestroyUploadRequest $request)
    {
        Upload::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
