@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.responden.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.respondens.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.id') }}
                        </th>
                        <td>
                            {{ $responden->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.nama') }}
                        </th>
                        <td>
                            {{ $responden->nama }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.kode') }}
                        </th>
                        <td>
                            {{ $responden->kode }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.usia') }}
                        </th>
                        <td>
                            {{ $responden->usia }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.his_adekuat') }}
                        </th>
                        <td>
                            {{ App\Models\Responden::HIS_ADEKUAT_RADIO[$responden->his_adekuat] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.pergerakan') }}
                        </th>
                        <td>
                            {{ App\Models\Responden::PERGERAKAN_RADIO[$responden->pergerakan] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.paritas') }}
                        </th>
                        <td>
                            {{ $responden->paritas }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.kardiotokografi') }}
                        </th>
                        <td>
                            {{ $responden->kardiotokografi }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.alamat') }}
                        </th>
                        <td>
                            {!! $responden->alamat !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.created_at') }}
                        </th>
                        <td>
                            {{ $responden->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.responden.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $responden->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.respondens.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#responden_iot_readings" role="tab" data-toggle="tab">
                {{ trans('cruds.iotReading.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#responden_sm_readings" role="tab" data-toggle="tab">
                {{ trans('cruds.smReading.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="responden_iot_readings">
            @includeIf('admin.respondens.relationships.respondenIotReadings', ['iotReadings' => $responden->respondenIotReadings])
        </div>
        <div class="tab-pane" role="tabpanel" id="responden_sm_readings">
            @includeIf('admin.respondens.relationships.respondenSmReadings', ['smReadings' => $responden->respondenSmReadings])
        </div>
    </div>
</div>

@endsection