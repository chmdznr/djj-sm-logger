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
    /**
     * @OA\Get(
     *     path="/iot_reading",
     *     summary="Get iot_reading data",
     *     tags={"IOT Reading"},
     *     @OA\Response(response="200", description="Success",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", type="array",
     *          @OA\Items(
     *            type="object",
     *            @OA\Property(property="id", type="integer", example=1),
     *            @OA\Property(property="respondent_id", type="integer", example=1),
     *            @OA\Property(property="respondent_name", type="string", example="Feliona"),
     *            @OA\Property(property="fetal_hr", type="integer", example=1),
     *            @OA\Property(property="resp_count", type="integer", example=1),
     *            @OA\Property(property="created_at", type="string", example="2023-08-24 02:06:10"),
     *            
     *          )
     *         )
     *     )
     * ),
     *   ),
     */
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
