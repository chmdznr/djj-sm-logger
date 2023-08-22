<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIotReadingRequest;
use App\Http\Requests\UpdateIotReadingRequest;
use App\Http\Resources\Admin\IotReadingResource;
use App\Models\IotReading;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IotReadingApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('iot_reading_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IotReadingResource(IotReading::with(['responden'])->get());
    }

    public function store(StoreIotReadingRequest $request)
    {
        $iotReading = IotReading::create($request->all());

        return (new IotReadingResource($iotReading))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IotReading $iotReading)
    {
        abort_if(Gate::denies('iot_reading_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IotReadingResource($iotReading->load(['responden']));
    }

    public function update(UpdateIotReadingRequest $request, IotReading $iotReading)
    {
        $iotReading->update($request->all());

        return (new IotReadingResource($iotReading))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IotReading $iotReading)
    {
        abort_if(Gate::denies('iot_reading_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $iotReading->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
