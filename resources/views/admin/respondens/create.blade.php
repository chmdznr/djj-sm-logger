@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.responden.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.respondens.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nama">{{ trans('cruds.responden.fields.nama') }}</label>
                <input class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" type="text" name="nama" id="nama" value="{{ old('nama', '') }}" required>
                @if($errors->has('nama'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nama') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.responden.fields.nama_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="kode">{{ trans('cruds.responden.fields.kode') }}</label>
                <input class="form-control {{ $errors->has('kode') ? 'is-invalid' : '' }}" type="text" name="kode" id="kode" value="{{ old('kode', '') }}" required>
                @if($errors->has('kode'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kode') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.responden.fields.kode_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="usia">{{ trans('cruds.responden.fields.usia') }}</label>
                <input class="form-control {{ $errors->has('usia') ? 'is-invalid' : '' }}" type="number" name="usia" id="usia" value="{{ old('usia', '') }}" step="1" required>
                @if($errors->has('usia'))
                    <div class="invalid-feedback">
                        {{ $errors->first('usia') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.responden.fields.usia_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.responden.fields.his_adekuat') }}</label>
                @foreach(App\Models\Responden::HIS_ADEKUAT_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('his_adekuat') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="his_adekuat_{{ $key }}" name="his_adekuat" value="{{ $key }}" {{ old('his_adekuat', '') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="his_adekuat_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('his_adekuat'))
                    <div class="invalid-feedback">
                        {{ $errors->first('his_adekuat') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.responden.fields.his_adekuat_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.responden.fields.pergerakan') }}</label>
                @foreach(App\Models\Responden::PERGERAKAN_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('pergerakan') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="pergerakan_{{ $key }}" name="pergerakan" value="{{ $key }}" {{ old('pergerakan', '') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="pergerakan_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('pergerakan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pergerakan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.responden.fields.pergerakan_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="paritas">{{ trans('cruds.responden.fields.paritas') }}</label>
                <input class="form-control {{ $errors->has('paritas') ? 'is-invalid' : '' }}" type="number" name="paritas" id="paritas" value="{{ old('paritas', '') }}" step="1">
                @if($errors->has('paritas'))
                    <div class="invalid-feedback">
                        {{ $errors->first('paritas') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.responden.fields.paritas_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kardiotokografi">{{ trans('cruds.responden.fields.kardiotokografi') }}</label>
                <input class="form-control {{ $errors->has('kardiotokografi') ? 'is-invalid' : '' }}" type="number" name="kardiotokografi" id="kardiotokografi" value="{{ old('kardiotokografi', '') }}" step="1">
                @if($errors->has('kardiotokografi'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kardiotokografi') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.responden.fields.kardiotokografi_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="alamat">{{ trans('cruds.responden.fields.alamat') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('alamat') ? 'is-invalid' : '' }}" name="alamat" id="alamat">{!! old('alamat') !!}</textarea>
                @if($errors->has('alamat'))
                    <div class="invalid-feedback">
                        {{ $errors->first('alamat') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.responden.fields.alamat_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.respondens.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $responden->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection