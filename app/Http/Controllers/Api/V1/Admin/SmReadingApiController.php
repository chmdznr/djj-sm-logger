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
    /**
     * @OA\Get(
     *     path="/sm-readings",
     *     summary="Get smarwatch reading data",
     *     tags={"SM Reading"},
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
     *     @OA\Response(response="200", description="Success",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", type="array",
     *          @OA\Items(
     *            type="object",
     *            @OA\Property(property="id", type="integer", example=1),
     *            @OA\Property(property="spo2", type="integer", example=125),
     *            @OA\Property(property="hr", type="integer", example=70),
     *            @OA\Property(property="skin_temp", type="integer", example=33),
     *            @OA\Property(property="created_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="updated_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="deleted_at", type="string", example=null),
     *            @OA\Property(property="responden_id", type="integer", example=1),
     *         @OA\Property(property="responden", type="object",
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
     *         )
     *     )
     * ),
     *      @OA\Response(response="401", description="Unauthenticated",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *      ),
     *     @OA\Response(response=403, description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="This action is unauthorized.")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * ),
     */

    public function index()
    {
        abort_if(Gate::denies('sm_reading_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SmReadingResource(SmReading::with(['responden'])->get());
    }

    /**
     * @OA\Post(
     *     path="/sm-readings",
     *     summary="Store smarwatch reading data",
     *     tags={"SM Reading"},
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
     *         description="User object",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"responden_id", "spo_2", "hr", "skin_temp"},
     *                 @OA\Property(
     *                     property="responden_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="spo_2",
     *                     type="integer",
     *                     example=125
     *                 ),
     *                 @OA\Property(
     *                     property="hr",
     *                     type="integer",
     *                     example=70
     *                 ),
     *                 @OA\Property(
     *                     property="skin_temp",
     *                     type="integer",
     *                     example=33
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Created",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", type="object",
     *            @OA\Property(property="responden_id", type="integer", example=1),
     *            @OA\Property(property="spo2", type="integer", example=125),
     *            @OA\Property(property="hr", type="integer", example=70),
     *            @OA\Property(property="skin_temp", type="integer", example=33),
     *            @OA\Property(property="created_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="updated_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="id", type="integer", example=1),
     *         )
     *     )
     *     ),
     *      @OA\Response(response="401", description="Unauthenticated",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *      ),
     *     @OA\Response(response=403, description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="This action is unauthorized.")
     *         )
     *     ),
     *     @OA\Response(response="422", description="Missing field",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The hr field is required."),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="hr", type="array",
     *                      @OA\Items(type="string", example="The hr field is required.")
     *                  ),
     *              )
     *          ),
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * 
     * ),
     */

    public function store(StoreSmReadingRequest $request)
    {
        $smReading = SmReading::create($request->all());

        return (new SmReadingResource($smReading))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/sm-readings/{id}",
     *     summary="Get smarwatch reading data",
     *     tags={"SM Reading"},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id data",
     *         required=true,
     *         example=1,
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
     *     @OA\Response(response="200", description="Success",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", type="object",
     *            @OA\Property(property="id", type="integer", example=1),
     *            @OA\Property(property="spo2", type="integer", example=125),
     *            @OA\Property(property="hr", type="integer", example=70),
     *            @OA\Property(property="skin_temp", type="integer", example=33),
     *            @OA\Property(property="created_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="updated_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="deleted_at", type="string", example=null),
     *            @OA\Property(property="responden_id", type="integer", example=1),
     *            @OA\Property(property="responden", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="nama", type="string", example="Valerica"),
     *                  @OA\Property(property="kode", type="string", example="ES101"),
     *                  @OA\Property(property="usia", type="integer", example=29),
     *                  @OA\Property(property="his_adekuat", type="string", example="0"),
     *                  @OA\Property(property="pergerakan", type="string", example="0"),
     *                  @OA\Property(property="paritas", type="integer", example=null),
     *                  @OA\Property(property="kardiotokografi", type="integer", example=null),
     *                  @OA\Property(property="alamat", type="string", example="Chateau de Versaille"),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-24 02:06:10"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-24 02:06:10"),
     *                  @OA\Property(property="deleted_at", type="string", example=null),
     *            
     *              )
     *            )
     *          )
     *     ),
     * 
     *      @OA\Response(response="401", description="Unauthenticated",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *      ),
     *     @OA\Response(response=403, description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="This action is unauthorized.")
     *         )
     *     ),
     *      @OA\Response(response=404, description="Resource Not Found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\SmReading] 1")
     *          )
     *      ),
     *      security={
     *         {"bearerAuth": {}}
     *     }
     * ),
     */

    public function show(SmReading $smReading)
    {
        abort_if(Gate::denies('sm_reading_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SmReadingResource($smReading->load(['responden']));
    }

    /**
     * @OA\PathItem(
     *   path="/sm-readings/{id}",
     *   @OA\Put(
     *     summary="Get smarwatch reading data",
     *     tags={"SM Reading"},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id data",
     *         required=true,
     *         example=1,
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
     *         description="User object",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"responden_id", "spo_2", "hr", "skin_temp"},
     *                 @OA\Property(
     *                     property="responden_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="spo_2",
     *                     type="integer",
     *                     example=125
     *                 ),
     *                 @OA\Property(
     *                     property="hr",
     *                     type="integer",
     *                     example=70
     *                 ),
     *                 @OA\Property(
     *                     property="skin_temp",
     *                     type="integer",
     *                     example=33
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="202", description="Accepted",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", type="object",
     *            @OA\Property(property="id", type="integer", example=1),
     *            @OA\Property(property="spo2", type="integer", example=99),
     *            @OA\Property(property="hr", type="integer", example=9),
     *            @OA\Property(property="skin_temp", type="integer", example=9),
     *            @OA\Property(property="created_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="updated_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="deleted_at", type="string", example=null),
     *            @OA\Property(property="responden_id", type="integer", example=1),
     *         )
     *     )
     *   ),
     *      @OA\Response(response="401", description="Unauthenticated",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *      ),
     *     @OA\Response(response=403, description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="This action is unauthorized.")
     *         )
     *     ),
     *      @OA\Response(response=404, description="Resource Not Found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\SmReading] 1")
     *          )
     *      ),
     *     @OA\Response(response="422", description="Missing field",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="message", type="string", example="The hr field is required."),
     *         @OA\Property(property="errors", type="object",
     *            @OA\Property(property="hr", type="array",
     *              @OA\Items(type="string", example="The hr field is required.")
     *            ),
     *         )
     *     ),
     *   ),
     *   security={
     *         {"bearerAuth": {}}
     *     }
     * 
     * ),
     *   @OA\Patch(
     *     summary="Get smarwatch reading data",
     *     tags={"SM Reading"},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id data",
     *         required=true,
     *         example=1,
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
     *         description="User object",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"responden_id", "spo_2", "hr", "skin_temp"},
     *                 @OA\Property(
     *                     property="responden_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="spo_2",
     *                     type="integer",
     *                     example=125
     *                 ),
     *                 @OA\Property(
     *                     property="hr",
     *                     type="integer",
     *                     example=70
     *                 ),
     *                 @OA\Property(
     *                     property="skin_temp",
     *                     type="integer",
     *                     example=33
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="202", description="Accepted",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", type="object",
     *            @OA\Property(property="id", type="integer", example=1),
     *            @OA\Property(property="spo2", type="integer", example=99),
     *            @OA\Property(property="hr", type="integer", example=9),
     *            @OA\Property(property="skin_temp", type="integer", example=9),
     *            @OA\Property(property="created_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="updated_at", type="string", example="2023-08-24 02:06:10"),
     *            @OA\Property(property="deleted_at", type="string", example=null),
     *            @OA\Property(property="responden_id", type="integer", example=1),
     *         )
     *     )
     *   ),
     *      @OA\Response(response="401", description="Unauthenticated",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *      ),
     *     @OA\Response(response=403, description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="This action is unauthorized.")
     *         )
     *     ),
     *      @OA\Response(response=404, description="Resource Not Found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\SmReading] 1")
     *          )
     *      ),
     *     @OA\Response(response="422", description="Missing field",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The hr field is required."),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="hr", type="array",
     *                      @OA\Items(type="string", example="The hr field is required.")
     *                  ),
     *              )
     *          ),
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     *   )
     * )
     */

    public function update(UpdateSmReadingRequest $request, SmReading $smReading)
    {
        $smReading->update($request->all());

        return (new SmReadingResource($smReading))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *     path="/sm-readings/{id}",
     *     summary="Delete smarwatch reading data",
     *     tags={"SM Reading"},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id data",
     *         required=true,
     *         example=1,
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
     *     @OA\Response(response="204", description="Success",
     *         @OA\JsonContent(example="")
     *     ),
     *      @OA\Response(response="401", description="Unauthenticated",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *         )
     *      ),
     *     @OA\Response(response=403, description="Unauthorized",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="message", type="integer", example="This action is unauthorized.")
     *         )
     *     ),
     *      @OA\Response(response=404, description="Resource Not Found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\SmReading] 1")
     *          )
     *      ),
     *      security={
     *         {"bearerAuth": {}}
     *     }
     * ),
     */

    public function destroy(SmReading $smReading)
    {
        abort_if(Gate::denies('sm_reading_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $smReading->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
