<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Pregnancy Api",
 *     version="1.0.0",
 *     description="Deskripsi API"
 * )
 * 
 * 
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Pregnancy API Server"
 * )
 */


//  @OA\SecurityScheme(
//     *     type="http",
//     *     description="Login with email and password to get the authentication token. 
//     *     Input the token without 'Bearer' prefix.",
//     *     in="header",
//     *     scheme="bearer",
//     *     securityScheme="bearerAuth",
//     *     name="Authorization",
//     * )
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
