@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.iotReading.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.iot-readings.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="responden_id">{{ trans('cruds.iotReading.fields.responden') }}</label>
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
                <span class="help-block">{{ trans('cruds.iotReading.fields.responden_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fetal_hr">{{ trans('cruds.iotReading.fields.fetal_hr') }}</label>
                <input class="form-control {{ $errors->has('fetal_hr') ? 'is-invalid' : '' }}" type="number" name="fetal_hr" id="fetal_hr" value="{{ old('fetal_hr', '0') }}" step="1" required>
                @if($errors->has('fetal_hr'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fetal_hr') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.iotReading.fields.fetal_hr_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="resp_count">{{ trans('cruds.iotReading.fields.resp_count') }}</label>
                <input class="form-control {{ $errors->has('resp_count') ? 'is-invalid' : '' }}" type="number" name="resp_count" id="resp_count" value="{{ old('resp_count', '0') }}" step="1" required>
                @if($errors->has('resp_count'))
                    <div class="invalid-feedback">
                        {{ $errors->first('resp_count') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.iotReading.fields.resp_count_helper') }}</span>
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