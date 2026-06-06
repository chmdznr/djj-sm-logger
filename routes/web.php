<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

// Auth::routes() was removed in Laravel 11+. Re-declare the authentication
// routes manually so the rest of the application can keep depending on them.
Route::group(['namespace' => 'App\Http\Controllers\Auth'], function () {
    // Authentication
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');

    // Registration (disabled, matching the previous ['register' => false] option)
    // Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    // Route::post('register', 'RegisterController@register');

    // Password Reset
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');

    // Password Confirmation
    Route::get('password/confirm', 'ConfirmPasswordController@showConfirmForm')->name('password.confirm');
    Route::post('password/confirm', 'ConfirmPasswordController@confirm');

    // Email Verification
    Route::get('email/verify', 'VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');

    // Change password (already present in app, keep behavior)
    Route::get('password/change', 'ChangePasswordController@showChangePasswordForm')->name('password.change');
    Route::post('password/change', 'ChangePasswordController@changePassword')->name('password.change.post');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'App\Http\Controllers\Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Responden
    Route::delete('respondens/destroy', 'RespondenController@massDestroy')->name('respondens.massDestroy');
    Route::post('respondens/media', 'RespondenController@storeMedia')->name('respondens.storeMedia');
    Route::post('respondens/ckmedia', 'RespondenController@storeCKEditorImages')->name('respondens.storeCKEditorImages');
    Route::post('respondens/parse-csv-import', 'RespondenController@parseCsvImport')->name('respondens.parseCsvImport');
    Route::post('respondens/process-csv-import', 'RespondenController@processCsvImport')->name('respondens.processCsvImport');
    Route::resource('respondens', 'RespondenController');

    // Iot Reading
    Route::delete('iot-readings/destroy', 'IotReadingController@massDestroy')->name('iot-readings.massDestroy');
    Route::post('iot-readings/parse-csv-import', 'IotReadingController@parseCsvImport')->name('iot-readings.parseCsvImport');
    Route::post('iot-readings/process-csv-import', 'IotReadingController@processCsvImport')->name('iot-readings.processCsvImport');
    Route::resource('iot-readings', 'IotReadingController');

    // Sm Reading
    Route::delete('sm-readings/destroy', 'SmReadingController@massDestroy')->name('sm-readings.massDestroy');
    Route::post('sm-readings/parse-csv-import', 'SmReadingController@parseCsvImport')->name('sm-readings.parseCsvImport');
    Route::post('sm-readings/process-csv-import', 'SmReadingController@processCsvImport')->name('sm-readings.processCsvImport');
    Route::resource('sm-readings', 'SmReadingController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'App\Http\Controllers\Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
