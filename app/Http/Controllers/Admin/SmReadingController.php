<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroySmReadingRequest;
use App\Http\Requests\StoreSmReadingRequest;
use App\Http\Requests\UpdateSmReadingRequest;
use App\Models\Responden;
use App\Models\SmReading;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SmReadingController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('sm_reading_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SmReading::with(['responden'])->select(sprintf('%s.*', (new SmReading)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'sm_reading_show';
                $editGate      = 'sm_reading_edit';
                $deleteGate    = 'sm_reading_delete';
                $crudRoutePart = 'sm-readings';

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
                return is_string($row->responden) ? $row->responden : ($row->responden->kode ?? '');
            });
            
            $table->editColumn('spo_2', function ($row) {
                return isset($row->spo_2) ? $row->spo_2 : '';
            });
            
            $table->editColumn('hr', function ($row) {
                return isset($row->hr) ? $row->hr : '';
            });
            
            $table->editColumn('skin_temp', function ($row) {
                return isset($row->skin_temp) ? $row->skin_temp : '';
            });
            
   
            
            $table->rawColumns(['actions', 'placeholder', 'responden']);

            return $table->make(true);
        }

        $respondens = Responden::get();

        return view('admin.smReadings.index', compact('respondens'));
    }

    public function create()
    {
        abort_if(Gate::denies('sm_reading_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respondens = Responden::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.smReadings.create', compact('respondens'));
    }

    public function store(StoreSmReadingRequest $request)
    {
        $smReading = SmReading::create($request->all());

        return redirect()->route('admin.sm-readings.index');
    }

    public function edit(SmReading $smReading)
    {
        abort_if(Gate::denies('sm_reading_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respondens = Responden::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $smReading->load('responden');

        return view('admin.smReadings.edit', compact('respondens', 'smReading'));
    }

    public function update(UpdateSmReadingRequest $request, SmReading $smReading)
    {
        $smReading->update($request->all());

        return redirect()->route('admin.sm-readings.index');
    }

    public function show(SmReading $smReading)
    {
        abort_if(Gate::denies('sm_reading_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $smReading->load('responden');

        return view('admin.smReadings.show', compact('smReading'));
    }

    public function destroy(SmReading $smReading)
    {
        abort_if(Gate::denies('sm_reading_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $smReading->delete();

        return back();
    }

    public function massDestroy(MassDestroySmReadingRequest $request)
    {
        $smReadings = SmReading::find(request('ids'));

        foreach ($smReadings as $smReading) {
            $smReading->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
