<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'App\Http\Controllers\Api\V1\Admin'], function () {

    Route::group(['middleware' => ['auth:sanctum']], function () {

        // Responden
        Route::post('respondens/media', 'RespondenApiController@storeMedia')->name('respondens.storeMedia');
        Route::apiResource('respondens', 'RespondenApiController');

        // Iot Reading
        Route::apiResource('iot-readings', 'IotReadingApiController');

        // Sm Reading
        Route::apiResource('sm-readings', 'SmReadingApiController');


        Route::post('revoke', 'AuthController@revoke');
    });

    // Auth
    Route::post('login', 'AuthController@login');
    // Registration disabled (matches web side ['register' => false]).
    // Self-service user creation is a privilege-escalation vector — admin role
    // assignment and password policy must be enforced through the admin UI.
    // Route::post('register', 'AuthController@register');
});
