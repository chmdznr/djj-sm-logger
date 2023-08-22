@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.smReading.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sm-readings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.smReading.fields.id') }}
                        </th>
                        <td>
                            {{ $smReading->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.smReading.fields.responden') }}
                        </th>
                        <td>
                            {{ $smReading->responden->nama ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.smReading.fields.spo_2') }}
                        </th>
                        <td>
                            {{ $smReading->spo_2 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.smReading.fields.hr') }}
                        </th>
                        <td>
                            {{ $smReading->hr }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.smReading.fields.skin_temp') }}
                        </th>
                        <td>
                            {{ $smReading->skin_temp }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sm-readings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection