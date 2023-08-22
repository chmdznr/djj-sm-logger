<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Responden
    Route::post('respondens/media', 'RespondenApiController@storeMedia')->name('respondens.storeMedia');
    Route::apiResource('respondens', 'RespondenApiController');

    // Iot Reading
    Route::apiResource('iot-readings', 'IotReadingApiController');

    // Sm Reading
    Route::apiResource('sm-readings', 'SmReadingApiController');
});
