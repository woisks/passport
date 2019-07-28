<?php
declare(strict_types=1);


Route::prefix('passport')
    ->middleware('throttle:60,1')
    ->namespace('Woisks\Passport\Http\Controllers')
    ->group(function () {

        Route::any('check/{type}/{username}', 'CheckController@check')->where(['type' => '[a-z]+']);
        Route::post('register', 'RegisterController@register')->middleware('captcha');
        Route::post('login', 'LoginController@login');
        Route::post('password/reset', 'PasswordController@reset')->middleware('captcha');
        Route::middleware('token')->group(function () {

            Route::any('logout', 'LogoutController@logout');
            Route::get('online', 'StatusController@online');
            Route::post('offline/{mac}', 'StatusController@offline')->where(['mac' => '[0-9]+']);
            Route::get('/', 'PassportController@get');
            Route::post('add', 'PassportController@add');
            Route::post('del', 'PassportController@del');
            Route::any('password/update', 'PasswordController@update');
            Route::post('bind', 'PassportController@bind')->middleware('captcha');
            Route::post('update', 'PassportController@update')->middleware('captcha');

        });

    });




