<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSmReadingRequest;
use App\Http\Requests\UpdateSmReadingRequest;
use App\Http\Resources\Admin\SmReadingResource;
use App\Models\SmReading;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SmReadingApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sm_reading_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SmReadingResource(SmReading::with(['responden'])->get());
    }

    public function store(StoreSmReadingRequest $request)
    {
        $smReading = SmReading::create($request->all());

        return (new SmReadingResource($smReading))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SmReading $smReading)
    {
        abort_if(Gate::denies('sm_reading_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SmReadingResource($smReading->load(['responden']));
    }

    public function update(UpdateSmReadingRequest $request, SmReading $smReading)
    {
        $smReading->update($request->all());

        return (new SmReadingResource($smReading))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SmReading $smReading)
    {
        abort_if(Gate::denies('sm_reading_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $smReading->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
