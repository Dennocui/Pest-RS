@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.pest.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pest.fields.id') }}
                        </th>
                        <td>
                            {{ $pest->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pest.fields.pest_name') }}
                        </th>
                        <td>
                            {{ $pest->pest_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pest.fields.pest_desc') }}
                        </th>
                        <td>
                            {!! $pest->pest_desc !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pest.fields.pest_photo') }}
                        </th>
                        <td>
                            @foreach($pest->pest_photo as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    <img src="{{ $media->getUrl('thumb') }}" width="50px" height="50px">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pest.fields.category') }}
                        </th>
                        <td>
                            {{ $pest->category->category ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection