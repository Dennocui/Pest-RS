@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.upload.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.uploads.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('pest_image') ? 'has-error' : '' }}">
                <label for="pest_image">{{ trans('cruds.upload.fields.pest_image') }}*</label>
                <div class="needsclick dropzone" id="pest_image-dropzone">

                </div>
                @if($errors->has('pest_image'))
                    <em class="invalid-feedback">
                        {{ $errors->first('pest_image') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.upload.fields.pest_image_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">{{ trans('cruds.upload.fields.description') }}*</label>
                <textarea id="description" name="description" class="form-control ckeditor">{{ old('description', isset($upload) ? $upload->description : '') }}</textarea>
                @if($errors->has('description'))
                    <em class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.upload.fields.description_helper') }}
                </p>
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
    var uploadedPestImageMap = {}
Dropzone.options.pestImageDropzone = {
    url: '{{ route('admin.uploads.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="pest_image[]" value="' + response.name + '">')
      uploadedPestImageMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedPestImageMap[file.name]
      }
      $('form').find('input[name="pest_image[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($upload) && $upload->pest_image)
      var files =
        {!! json_encode($upload->pest_image) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="pest_image[]" value="' + file.file_name + '">')
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