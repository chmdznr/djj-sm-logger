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
     *     path="/iot-readings",
     *     summary="Get iot_reading data",
     *     tags={"IOT Reading"},
     *     @OA\Response(response="200", description="Success",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", type="array",
     *          @OA\Items(
     *            type="object",
     *            @OA\Property(property="id", type="integer", example=1),
     *            @OA\Property(property="fetal_hr", type="integer", example=99),
     *            @OA\Property(property="resp_count", type="integer", example=9),
     *            @OA\Property(property="created_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="updated_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="deleted_at", type="string", example=null),
     *            @OA\Property(property="responden_id", type="integer", example=1),
     *         @OA\Property(property="responden", type="array",
     *          @OA\Items(
     *            type="object",
     *            @OA\Property(property="id", type="integer", example=1),
     *            @OA\Property(property="nama", type="string", example="Valerica"),
     *            @OA\Property(property="kode", type="string", example="ES101"),
     *            @OA\Property(property="usia", type="integer", example=29),
     *            @OA\Property(property="his_adekuat", type="string", example="0"),
     *            @OA\Property(property="pergerakan", type="string", example="0"),
     *            @OA\Property(property="paritas", type="integer", example=null),
     *            @OA\Property(property="kardiotokografi", type="integer", example=null),
     *            @OA\Property(property="alamat", type="string", example="Chateau de Versaille"),
     *            @OA\Property(property="created_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="updated_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="deleted_at", type="string", example=null),
     *            
     *          )
     *         )
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
