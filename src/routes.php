<?php
declare(strict_types=1);


Route::prefix('passport')
     ->namespace('Woisks\Passport\Http\Controllers')
     ->group(function () {

         Route::any('check/{type}', 'CheckController@check')->where(['type' => '[a-z]+']);
         Route::post('register', 'RegisterController@register')->middleware('captcha');
         Route::post('login', 'LoginController@login');
         Route::any('password/reset', 'PasswordController@reset')->middleware('captcha');
         Route::middleware('token')->group(function () {

             Route::any('logout', 'LogoutController@logout');
             Route::get('online', 'StatusController@online');
             Route::post('offline', 'StatusController@offline');
             Route::get('/', 'PassportController@get');
             Route::post('add', 'PassportController@add');
             Route::post('del', 'PassportController@del');
             Route::any('password/update', 'PasswordController@update');
             Route::post('bind', 'PassportController@bind')->middleware('captcha');
             Route::post('update', 'PassportController@update')->middleware('captcha');

         });

     });




