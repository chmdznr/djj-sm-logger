<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreRespondenRequest;
use App\Http\Requests\UpdateRespondenRequest;
use App\Http\Resources\Admin\RespondenResource;
use App\Models\Responden;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RespondenApiController extends Controller
{
    use MediaUploadingTrait;

    /**
     * @OA\Get(
     *     path="/respondens",
     *     summary="Get respondens data",
     *     tags={"Respondens"},
     *     @OA\Response(response="200", description="Success",
     *     @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="data", type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="nama", type="string", example="Jahfal"),
     *                  @OA\Property(property="kode", type="string", example="332"),
     *                  @OA\Property(property="usia", type="integer", example=19),
     *                  @OA\Property(property="his_adekuat", type="integer", example=null),
     *                  @OA\Property(property="pergerakan", type="integer", example=null),
     *                  @OA\Property(property="paritas", type="integer", example=null),
     *                  @OA\Property(property="alamat", type="integer", example=null),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-28 20:55:36"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-28 20:55:36"),
     *                  @OA\Property(property="deleted_at", type="integer", example=null),
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
        abort_if(Gate::denies('responden_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RespondenResource(Responden::all());
    }

    /**
     * @OA\Post(
     *     path="/respondens",
     *     summary="Store data for iot reading",
     *     tags={"Respondens"},
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
     *                 required={"nama", "kode", "usia", "paritas", "kardiotokografi"},
     *                 @OA\Property(
     *                     property="nama",
     *                     type="string",
     *                     example="Jahfal"
     *                 ),
     *                 @OA\Property(
     *                     property="kode",
     *                     type="string",
     *                     example="332"
     *                 ),
     *                 @OA\Property(
     *                     property="usia",
     *                     type="integer",
     *                     example=19
     *                 ),
     *                 @OA\Property(
     *                     property="paritas",
     *                     type="integer",
     *                     example=null
     *                 ),
     *                 @OA\Property(
     *                     property="kardiotokografi",
     *                     type="integer",
     *                     example=null
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(response="500", description="Internal Server Error"),
     *      @OA\Response(response="201", description="Created", 
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="data", type="object",
     *              @OA\Property(property="nama", type="string", example="Jahfal"),
     *              @OA\Property(property="kode", type="string", example="332"),
     *              @OA\Property(property="usia", type="integer", example=19),
     *              @OA\Property(property="paritas", type="integer", example=null),
     *              @OA\Property(property="kardiotografi", type="integer", example=null),
     *              @OA\Property(property="created_at", type="string", example="2023-08-28 20:55:36"),
     *              @OA\Property(property="updated_at", type="string", example="2023-08-28 20:55:36"),
     *              @OA\Property(property="id", type="integer", example=1),
     *         )
     *     )
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

    public function store(StoreRespondenRequest $request)
    {
        $responden = Responden::create($request->all());

        return (new RespondenResource($responden))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Responden $responden)
    {
        abort_if(Gate::denies('responden_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RespondenResource($responden);
    }

    /**
     * @OA\PathItem(
     *   path="/respondens/{id}",
     *  @OA\Put(
     *     tags={"Respondens"},
     *     summary="Update data respondens",
     *     description="Updates an existing respondens",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the respondens",
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
     *         description="Updated user object",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"nama", "kode", "usia", "paritas", "kardiotokografi"},
     *                 @OA\Property(
     *                     property="nama",
     *                     type="string",
     *                     example="Jahfal"
     *                 ),
     *                 @OA\Property(
     *                     property="kode",
     *                     type="string",
     *                     example="334"
     *                 ),
     *                 @OA\Property(
     *                     property="usia",
     *                     type="integer",
     *                     example=20
     *                 ),
     *                 @OA\Property(
     *                     property="paritas",
     *                     type="integer",
     *                     example=null
     *                 ),
     *                 @OA\Property(
     *                     property="kardiotokografi",
     *                     type="integer",
     *                     example=null
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Accepted",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="nama", type="string", example="Jahfal"),
     *                      @OA\Property(property="kode", type="string", example="334"),
     *                      @OA\Property(property="usia", type="integer", example=20),
     *                      @OA\Property(property="his_adekuat", type="integer", example=null),
     *                      @OA\Property(property="pergerakan", type="integer", example=null),
     *                      @OA\Property(property="paritas", type="integer", example=null),
     *                      @OA\Property(property="alamat", type="integer", example=null),
     *                      @OA\Property(property="created_at", type="string", example="2023-08-28 20:55:36"),
     *                      @OA\Property(property="updated_at", type="string", example="2023-08-28 14:51:56"),
     *                      @OA\Property(property="deleted_at", type="integer", example=null),
     *                 )
     *             )    
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
     * 
     *  
     * 
     * )
     */

    public function update(UpdateRespondenRequest $request, Responden $responden)
    {
        $responden->update($request->all());

        return (new RespondenResource($responden))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Responden $responden)
    {
        abort_if(Gate::denies('responden_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $responden->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
