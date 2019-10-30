@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.pest.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.pests.update", [$pest->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('pest_name') ? 'has-error' : '' }}">
                <label for="pest_name">{{ trans('cruds.pest.fields.pest_name') }}*</label>
                <input type="text" id="pest_name" name="pest_name" class="form-control" value="{{ old('pest_name', isset($pest) ? $pest->pest_name : '') }}" required>
                @if($errors->has('pest_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('pest_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.pest.fields.pest_name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('pest_desc') ? 'has-error' : '' }}">
                <label for="pest_desc">{{ trans('cruds.pest.fields.pest_desc') }}</label>
                <textarea id="pest_desc" name="pest_desc" class="form-control ckeditor">{{ old('pest_desc', isset($pest) ? $pest->pest_desc : '') }}</textarea>
                @if($errors->has('pest_desc'))
                    <em class="invalid-feedback">
                        {{ $errors->first('pest_desc') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.pest.fields.pest_desc_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('pest_photo') ? 'has-error' : '' }}">
                <label for="pest_photo">{{ trans('cruds.pest.fields.pest_photo') }}*</label>
                <div class="needsclick dropzone" id="pest_photo-dropzone">

                </div>
                @if($errors->has('pest_photo'))
                    <em class="invalid-feedback">
                        {{ $errors->first('pest_photo') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.pest.fields.pest_photo_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                <label for="category">{{ trans('cruds.pest.fields.category') }}</label>
                <select name="category_id" id="category" class="form-control select2">
                    @foreach($categories as $id => $category)
                        <option value="{{ $id }}" {{ (isset($pest) && $pest->category ? $pest->category->id : old('category_id')) == $id ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                @if($errors->has('category_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('category_id') }}
                    </em>
                @endif
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection

@section('scripts')
<script>
    var uploadedPestPhotoMap = {}
Dropzone.options.pestPhotoDropzone = {
    url: '{{ route('admin.pests.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="pest_photo[]" value="' + response.name + '">')
      uploadedPestPhotoMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedPestPhotoMap[file.name]
      }
      $('form').find('input[name="pest_photo[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($pest) && $pest->pest_photo)
      var files =
        {!! json_encode($pest->pest_photo) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="pest_photo[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@stop