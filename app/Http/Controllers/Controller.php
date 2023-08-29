<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="DJJ Logger API",
 *     version="1.0.0",
 *     description="Data logger management for fetal heart rate using IoT and smartwatch"
 * )
 * 
 *  @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token. 
 *     Input the token without 'Bearer' prefix.",
 *     in="header",
 *     scheme="bearer",
 *     securityScheme="bearerAuth",
 *     name="Authorization",
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Fetal Heart Rate Data Logger"
 * )
 */



class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}