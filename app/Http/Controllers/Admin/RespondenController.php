<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRespondenRequest;
use App\Http\Requests\StoreRespondenRequest;
use App\Http\Requests\UpdateRespondenRequest;
use App\Models\Responden;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RespondenController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('responden_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Responden::query()->select(sprintf('%s.*', (new Responden)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'responden_show';
                $editGate      = 'responden_edit';
                $deleteGate    = 'responden_delete';
                $crudRoutePart = 'respondens';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('nama', function ($row) {
                return $row->nama ? $row->nama : '';
            });
            $table->editColumn('kode', function ($row) {
                return $row->kode ? $row->kode : '';
            });
            $table->editColumn('usia', function ($row) {
                return $row->usia ? $row->usia : '';
            });
            $table->editColumn('his_adekuat', function ($row) {
                return $row->his_adekuat ? Responden::HIS_ADEKUAT_RADIO[$row->his_adekuat] : '';
            });
            $table->editColumn('pergerakan', function ($row) {
                return $row->pergerakan ? Responden::PERGERAKAN_RADIO[$row->pergerakan] : '';
            });
            $table->editColumn('paritas', function ($row) {
                return $row->paritas ? $row->paritas : '';
            });
            $table->editColumn('kardiotokografi', function ($row) {
                return $row->kardiotokografi ? $row->kardiotokografi : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.respondens.index');
    }

    public function create()
    {
        abort_if(Gate::denies('responden_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.respondens.create');
    }

    public function store(StoreRespondenRequest $request)
    {
        $responden = Responden::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $responden->id]);
        }

        return redirect()->route('admin.respondens.index');
    }

    public function edit(Responden $responden)
    {
        abort_if(Gate::denies('responden_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.respondens.edit', compact('responden'));
    }

    public function update(UpdateRespondenRequest $request, Responden $responden)
    {
        $responden->update($request->all());

        return redirect()->route('admin.respondens.index');
    }

    public function show(Responden $responden)
    {
        abort_if(Gate::denies('responden_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $responden->load('respondenIotReadings', 'respondenSmReadings');

        return view('admin.respondens.show', compact('responden'));
    }

    public function destroy(Responden $responden)
    {
        abort_if(Gate::denies('responden_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $responden->delete();

        return back();
    }

    public function massDestroy(MassDestroyRespondenRequest $request)
    {
        $respondens = Responden::find(request('ids'));

        foreach ($respondens as $responden) {
            $responden->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('responden_create') && Gate::denies('responden_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Responden();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
