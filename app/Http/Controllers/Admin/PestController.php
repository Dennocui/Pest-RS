<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPestRequest;
use App\Http\Requests\StorePestRequest;
use App\Http\Requests\UpdatePestRequest;
use App\Pest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PestController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('pest_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pests = Pest::all();

        return view('admin.pests.index', compact('pests'));
    }

    public function create()
    {
        abort_if(Gate::denies('pest_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::all()->pluck('category', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.pests.create', compact('categories'));
    }

    public function store(StorePestRequest $request)
    {
        $pest = Pest::create($request->all());

        foreach ($request->input('pest_photo', []) as $file) {
            $pest->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('pest_photo');
        }

        return redirect()->route('admin.pests.index');
    }

    public function edit(Pest $pest)
    {
        abort_if(Gate::denies('pest_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::all()->pluck('category', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pest->load('category');

        return view('admin.pests.edit', compact('categories', 'pest'));
    }

    public function update(UpdatePestRequest $request, Pest $pest)
    {
        $pest->update($request->all());

        if (count($pest->pest_photo) > 0) {
            foreach ($pest->pest_photo as $media) {
                if (!in_array($media->file_name, $request->input('pest_photo', []))) {
                    $media->delete();
                }
            }
        }

        $media = $pest->pest_photo->pluck('file_name')->toArray();

        foreach ($request->input('pest_photo', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $pest->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('pest_photo');
            }
        }

        return redirect()->route('admin.pests.index');
    }

    public function show(Pest $pest)
    {
        abort_if(Gate::denies('pest_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pest->load('category');

        return view('admin.pests.show', compact('pest'));
    }

    public function destroy(Pest $pest)
    {
        abort_if(Gate::denies('pest_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pest->delete();

        return back();
    }

    public function massDestroy(MassDestroyPestRequest $request)
    {
        Pest::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
