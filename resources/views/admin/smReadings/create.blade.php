@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.smReading.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sm-readings.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="responden_id">{{ trans('cruds.smReading.fields.responden') }}</label>
                <select class="form-control select2 {{ $errors->has('responden') ? 'is-invalid' : '' }}" name="responden_id" id="responden_id" required>
                    @foreach($respondens as $id => $entry)
                        <option value="{{ $id }}" {{ old('responden_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('responden'))
                    <div class="invalid-feedback">
                        {{ $errors->first('responden') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.smReading.fields.responden_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="spo_2">{{ trans('cruds.smReading.fields.spo_2') }}</label>
                <input class="form-control {{ $errors->has('spo_2') ? 'is-invalid' : '' }}" type="number" name="spo_2" id="spo_2" value="{{ old('spo_2', '0') }}" step="0.01" required>
                @if($errors->has('spo_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('spo_2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.smReading.fields.spo_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="hr">{{ trans('cruds.smReading.fields.hr') }}</label>
                <input class="form-control {{ $errors->has('hr') ? 'is-invalid' : '' }}" type="number" name="hr" id="hr" value="{{ old('hr', '0') }}" step="1" required>
                @if($errors->has('hr'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hr') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.smReading.fields.hr_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="skin_temp">{{ trans('cruds.smReading.fields.skin_temp') }}</label>
                <input class="form-control {{ $errors->has('skin_temp') ? 'is-invalid' : '' }}" type="number" name="skin_temp" id="skin_temp" value="{{ old('skin_temp', '0') }}" step="0.01" required>
                @if($errors->has('skin_temp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('skin_temp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.smReading.fields.skin_temp_helper') }}</span>
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