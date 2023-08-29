<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;

class AuthController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Create new user",
     *     tags={"Auth"},
     *      @OA\RequestBody(
     *         required=true,
     *         description="User object",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "email", "password", "c_password"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Rayna Wynnie Dewantoro"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="rayna@wynniecomp.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="passwordjuga"
     *                 ),
     *                 @OA\Property(
     *                     property="c_password",
     *                     type="string",
     *                     example="passwordjuga"
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(response="500", description="Internal Server Error"),
     *      @OA\Response(response="201", description="Created", @OA\JsonContent(
     *          @OA\Property(property="access_token", type="string", example="1|khFOkZB9CNfknSsaFVaQVmTo7ZYVUMYs6xuTxPHu."),
     *          @OA\Property(property="token_type", type="string", example="Bearer"),
     *          @OA\Property(property="user", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Rayna Wynnie Dewantoro"),
     *                  @OA\Property(property="email", type="string", example="rayna@wynniecomp.com"),
     *                  @OA\Property(property="email_verified_at", type="string", example=null),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-24T02:05:01.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-24T02:05:01.000000Z"),
     *            
     *          )
     *      )),
     *     @OA\Response(response="422", description="Unprocessable Content",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="message", type="string", example="The email field is required."),
     *         @OA\Property(property="errors", type="object",
     *            @OA\Property(property="email", type="array",
     *              @OA\Items(type="string", example="The email field is required.")
     *            ),
     *         )
     *     ),
     *     ),
     *    
     *     ),
     *   )
     */
    public function register(AuthRegisterRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync(1);
        
        $success['access_token'] = $user->createToken('MyApp')->plainTextToken;
        $success['token_type'] = 'Bearer';
        $success['user'] = $user;

        return response($success, 201);
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Get user and token",
     *     tags={"Auth"},
     *      @OA\RequestBody(
     *         required=true,
     *         description="User object",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="admin@admin.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="password"
     *                 )
     *             )
     *         )
     *     ),
     *      @OA\Response(response="500", description="Internal Server Error"),
     *      @OA\Response(response="200", description="Success", @OA\JsonContent(
     *          @OA\Property(property="access_token", type="string", example="1|khFOkZB9CNfknSsaFVaQVmTo7ZYVUMYs6xuTxPHu."),
     *          @OA\Property(property="token_type", type="string", example="Bearer"),
     *          @OA\Property(property="user", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Rayna Wynnie Dewantoro"),
     *                  @OA\Property(property="email", type="string", example="rayna@wynniecomp.com"),
     *                  @OA\Property(property="email_verified_at", type="string", example=null),
     *                  @OA\Property(property="created_at", type="string", example="2023-08-24T02:05:01.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", example="2023-08-24T02:05:01.000000Z"),
     *            
     *          )
     *      )),
     *     @OA\Response(response="422", description="Unprocessable Content",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="message", type="string", example="The email field is required."),
     *         @OA\Property(property="errors", type="object",
     *            @OA\Property(property="email", type="array",
     *              @OA\Items(type="string", example="The email field is required.")
     *            ),
     *         )
     *     ),
     *     ),
     *    
     *     ),
     *   )
     */
    public function login(AuthLoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['access_token'] = $user->createToken('MyApp')->plainTextToken;
            $success['token_type'] = 'Bearer';
            $success['user'] = $user;

            return response($success, 200);
        } else {
            $res['message'] = "Unauthenticated.";
            return response($res, 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/revoke",
     *     summary="revoke token user",
     *     tags={"Auth"},
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
     *     @OA\Response(response="500", description="Internal Server Error"),
     *     @OA\Response(response="204", description="Success", @OA\JsonContent()),
     *     @OA\Response(response="401", description="Unauthorized",
     *        @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="message", type="integer", example="Unauthenticated.")
     *        )
     *     ),
     *      
     *     security={
     *        {"bearerAuth": {}}
     *     }
     *    
     *    ),
     *  )
     */

    public function revoke()
    {
        Auth::user()->currentAccessToken()->delete();
        return response('', 204);
    }
}