<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'UserController@login')->name('loginApi');
Route::post('register', 'UserController@register');
Route::group([

    'middleware' => ['jwt.verify','cors']], function ($router) {

        Route::post('visitors_add', 'VisitorsController@save_visitor');
        Route::post('visitors_update','VisitorsController@update_visitor');
        Route::post('visitors_listening','VisitorsController@listening_visitor');
        Route::post('visitors_details','VisitorsController@details_visitor');
    
});
