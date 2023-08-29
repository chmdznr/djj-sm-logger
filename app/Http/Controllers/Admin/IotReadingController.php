<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyIotReadingRequest;
use App\Http\Requests\StoreIotReadingRequest;
use App\Http\Requests\UpdateIotReadingRequest;
use App\Models\IotReading;
use App\Models\Responden;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class IotReadingController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('iot_reading_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = IotReading::with(['responden'])->select(sprintf('%s.*', (new IotReading)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'iot_reading_show';
                $editGate      = 'iot_reading_edit';
                $deleteGate    = 'iot_reading_delete';
                $crudRoutePart = 'iot-readings';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return isset($row->id) ? $row->id : '';
            });
            
            $table->addColumn('responden_nama', function ($row) {
                return isset($row->responden) ? ($row->responden->nama ?? '') : '';
            });
            
            $table->editColumn('responden.kode', function ($row) {
                return isset($row->responden) ? (is_string($row->responden) ? $row->responden : ($row->responden->kode ?? '')) : '';
            });
            
            $table->editColumn('fetal_hr', function ($row) {
                return isset($row->fetal_hr) ? $row->fetal_hr : '';
            });
            
            $table->editColumn('resp_count', function ($row) {
                return isset($row->resp_count) ? $row->resp_count : '';
            });
            

            $table->rawColumns(['actions', 'placeholder', 'responden']);

            return $table->make(true);
        }

        $respondens = Responden::get();

        return view('admin.iotReadings.index', compact('respondens'));
    }

    public function create()
    {
        abort_if(Gate::denies('iot_reading_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respondens = Responden::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.iotReadings.create', compact('respondens'));
    }

    public function store(StoreIotReadingRequest $request)
    {
        $iotReading = IotReading::create($request->all());

        return redirect()->route('admin.iot-readings.index');
    }

    public function edit(IotReading $iotReading)
    {
        abort_if(Gate::denies('iot_reading_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respondens = Responden::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $iotReading->load('responden');

        return view('admin.iotReadings.edit', compact('iotReading', 'respondens'));
    }

    public function update(UpdateIotReadingRequest $request, IotReading $iotReading)
    {
        $iotReading->update($request->all());

        return redirect()->route('admin.iot-readings.index');
    }

    public function show(IotReading $iotReading)
    {
        abort_if(Gate::denies('iot_reading_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $iotReading->load('responden');

        return view('admin.iotReadings.show', compact('iotReading'));
    }

    public function destroy(IotReading $iotReading)
    {
        abort_if(Gate::denies('iot_reading_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $iotReading->delete();

        return back();
    }

    public function massDestroy(MassDestroyIotReadingRequest $request)
    {
        $iotReadings = IotReading::find(request('ids'));

        foreach ($iotReadings as $iotReading) {
            $iotReading->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}