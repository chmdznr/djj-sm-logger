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
     *          type="object",
     *          @OA\Property(property="data", type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=2),
     *                  @OA\Property(property="fetal_hr", type="integer", example=20),
     *                  @OA\Property(property="resp_count", type="integer", example=99),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-28 13:43:25"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-28 13:43:25"),
     *                  @OA\Property(property="deleted_at", type="string", example=null),
     *                  @OA\Property(property="responden_id", type="integer", example=1),
     *                  @OA\Property(property="responden", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="nama", type="string", example="Jahfal"),
     *                      @OA\Property(property="kode", type="string", example="332"),
     *                      @OA\Property(property="usia", type="integer", example=19),
     *                      @OA\Property(property="his_adekuat", type="integer", example=null),
     *                      @OA\Property(property="pergerakan", type="integer", example=null),
     *                      @OA\Property(property="paritas", type="integer", example=null),
     *                      @OA\Property(property="alamat", type="integer", example=null),
     *                      @OA\Property(property="created_at", type="string", example="2023-08-28 20:55:36"),
     *                      @OA\Property(property="updated_at", type="string", example="2023-08-28 20:55:36"),
     *                      @OA\Property(property="deleted_at", type="integer", example=null),
     *            
     *                 )
     *              )
     *         )
     *     ) 
     *     ),
     *     @OA\Response(response="401", description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *      ),
     *   ),
     */
    public function index()
    {
        abort_if(Gate::denies('iot_reading_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IotReadingResource(IotReading::with(['responden'])->get());
    }

    /**
     * @OA\Post(
     *     path="/iot-readings",
     *     summary="Store data for iot reading",
     *     tags={"IOT Reading"},
     *      @OA\Parameter(
     *         name="Authorization",
     *         required=true,
     *         in="header",
     *         description="Set bearer token in header",
     *         example="Bearer 9|laravel_sanctum_6MQgJnERrszCWjo1nXCOgnY9AJtkV0vnz5K9X7IY10949072",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *         description="User object",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"responden_id", "fetal_hr", "resp_count"},
     *                 @OA\Property(
     *                     property="responden_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="fetal_hr",
     *                     type="integer",
     *                     example=20
     *                 ),
     *                 @OA\Property(
     *                     property="resp_count",
     *                     type="integer",
     *                     example=99
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(response="500", description="Internal Server Error"),
     *      @OA\Response(response="201", description="Created", 
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="responden_id", type="integer", example=1),
     *                  @OA\Property(property="fetal_hr", type="integer", example=20),
     *                  @OA\Property(property="resp_count", type="integer", example=99),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-28 13:43:25"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-28 13:43:25"),
     *                  @OA\Property(property="id", type="integer", example=2),
     *         )
     *          )
     *      ),
     *      @OA\Response(response="401", description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *      ),
     *      @OA\Response(response="422", description="Unprocessable Content",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="string", example="The field is required.")
     *         )
     *      ),
     *    
     *     ),
     *   )
     */

    public function store(StoreIotReadingRequest $request)
    {
        $iotReading = IotReading::create($request->all());

        return (new IotReadingResource($iotReading))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *      path="/iot-readings/{id}",
     *      tags={"IOT Reading"},
     *      summary="Get iot_reading by id",
     *      description="Returns project data",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id data",
     *         required=true,
     *         example=2,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=2),
     *                  @OA\Property(property="fetal_hr", type="integer", example=20),
     *                  @OA\Property(property="resp_count", type="integer", example=99),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-28 13:43:25"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-28 13:43:25"),
     *                  @OA\Property(property="deleted_at", type="string", example=null),
     *                  @OA\Property(property="responden_id", type="integer", example=1),
     *                  @OA\Property(property="responden", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="nama", type="string", example="Jahfal"),
     *                      @OA\Property(property="kode", type="string", example="332"),
     *                      @OA\Property(property="usia", type="integer", example=19),
     *                      @OA\Property(property="his_adekuat", type="integer", example=null),
     *                      @OA\Property(property="pergerakan", type="integer", example=null),
     *                      @OA\Property(property="paritas", type="integer", example=null),
     *                      @OA\Property(property="alamat", type="integer", example=null),
     *                      @OA\Property(property="created_at", type="string", example="2023-08-28 20:55:36"),
     *                      @OA\Property(property="updated_at", type="string", example="2023-08-28 20:55:36"),
     *                      @OA\Property(property="deleted_at", type="integer", example=null),
     *            
     *                  )
     *            
     *              )
     *          )
     *       ),
     *      @OA\Response(response=500, description="Internal Server Error"),
     *      @OA\Response(response=404, description="Resource Not Found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for id")
     *          )
     *      ),
     *  ),
     */

    public function show(IotReading $iotReading)
    {
        abort_if(Gate::denies('iot_reading_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IotReadingResource($iotReading->load(['responden']));
    }

    /**
     * @OA\PathItem(
     *   path="/iot-readings/{id}",
     *  @OA\Put(
     *     tags={"IOT Reading"},
     *     summary="Update data iot_reading",
     *     description="Updates an existing iot_reading",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the data",
     *         required=true,
     *         example=2,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Parameter(
     *         name="Authorization",
     *         required=true,
     *         in="header",
     *         description="Set bearer token in header",
     *         example="Bearer 9|laravel_sanctum_6MQgJnERrszCWjo1nXCOgnY9AJtkV0vnz5K9X7IY10949072",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated user object",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="responden_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="fetal_hr",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="resp_count",
     *                     type="integer",
     *                     example=1
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Accepted",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=2),
     *                  @OA\Property(property="fetal_hr", type="integer", example=1),
     *                  @OA\Property(property="resp_count", type="integer", example=1),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-28 13:43:25"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-28 14:03:30"),
     *                  @OA\Property(property="deleted_at", type="string", example=null),
     *                  @OA\Property(property="responden_id", type="integer", example=1),
     *              ) 
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for id")
     *          )
     *     ),
     *     @OA\Response(response="401", description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(response="422", description="Unprocessable Content",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="string", example="The field is required.")
     *         )
     *      ),
     *     @OA\Response(response=500, description="Internal Server Error"),
     *     
     *    ),
     *     
     *  ),
     *  @OA\Patch(
     *     tags={"IOT Reading"},
     *     path="/iot-readings/{id}",
     *     summary="Update data iot_reading",
     *     description="Updates an existing iot_reading",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the data",
     *         required=true,
     *         example=2,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Parameter(
     *         name="Authorization",
     *         required=true,
     *         in="header",
     *         description="Set bearer token in header",
     *         example="Bearer 9|laravel_sanctum_6MQgJnERrszCWjo1nXCOgnY9AJtkV0vnz5K9X7IY10949072",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated user object",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="responden_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="fetal_hr",
     *                     type="integer",
     *                     example=2
     *                 ),
     *                 @OA\Property(
     *                     property="resp_count",
     *                     type="integer",
     *                     example=1
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Accepted",
     *         @OA\JsonContent(
     *             type="object",
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=2),
     *                  @OA\Property(property="fetal_hr", type="integer", example=2),
     *                  @OA\Property(property="resp_count", type="integer", example=1),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-28 13:43:25"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-28 14:11:33"),
     *                  @OA\Property(property="deleted_at", type="string", example=null),
     *                  @OA\Property(property="responden_id", type="integer", example=1),
     *              ) 
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for id")
     *          )
     *     ),
     *     @OA\Response(response="401", description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal Server Error"),
     * 
     *     
     *    ),
     *     
     *  ),
     * )
     */

    public function update(UpdateIotReadingRequest $request, IotReading $iotReading)
    {
        $iotReading->update($request->all());

        return (new IotReadingResource($iotReading))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *     tags={"IOT Reading"},
     *     path="/iot-readings/{id}",
     *     summary="Delete data iot_reading",
     *     description="Delete an existing iot_reading by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the data",
     *         required=true,
     *         example=2,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Parameter(
     *         name="Authorization",
     *         required=true,
     *         in="header",
     *         description="Set bearer token in header",
     *         example="Bearer 9|laravel_sanctum_6MQgJnERrszCWjo1nXCOgnY9AJtkV0vnz5K9X7IY10949072",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Data deleted successfully",
     *         @OA\JsonContent(example="")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data not found",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for id")
     *          )
     *     ),
     *     @OA\Response(response="401", description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal Server Error"),
     * 
     *     
     *    ),
     *     
     *  )
     */

    public function destroy(IotReading $iotReading)
    {
        abort_if(Gate::denies('iot_reading_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $iotReading->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}