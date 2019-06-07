<?php
declare(strict_types=1);


Route::prefix('passport')
     ->namespace('Woisks\Passport\Http\Controllers')
     ->group(function () {

         Route::any('check/{type}', 'CheckController@check');
         Route::post('register', 'RegisterController@register');
         Route::post('login', 'LoginController@login');

         Route::middleware('token')->group(function () {//token

             Route::any('logout', 'LogoutController@logout');

             Route::get('online', 'StatusController@online');
             Route::post('offline', 'StatusController@offline');

             Route::get('/', 'PassportController@get');
             Route::post('add', 'PassportController@add');
             Route::post('del', 'PassportController@del');
             Route::any('password/update', 'PasswordController@update');

             Route::middleware('captcha')->group(function () {//captcha
                 Route::any('password/reset', 'PasswordController@reset');
                 Route::post('bind', 'PassportController@bind');
                 Route::post('update', 'PassportController@update');

             });

         });

     });




