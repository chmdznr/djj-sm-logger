@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.iotReading.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.iot-readings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.iotReading.fields.id') }}
                        </th>
                        <td>
                            {{ $iotReading->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.iotReading.fields.responden') }}
                        </th>
                        <td>
                            {{ $iotReading->responden->nama ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.iotReading.fields.fetal_hr') }}
                        </th>
                        <td>
                            {{ $iotReading->fetal_hr }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.iotReading.fields.resp_count') }}
                        </th>
                        <td>
                            {{ $iotReading->resp_count }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.iotReading.fields.created_at') }}
                        </th>
                        <td>
                            {{ $iotReading->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.iotReading.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $iotReading->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.iot-readings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection