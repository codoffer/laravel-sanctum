<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\v1\AuthController;

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

Route::group([
    'prefix' => 'v1',
    'namespace' => 'API\v1'
], function ($router) {
    Route::post('user/register', 'AuthController@register');
    Route::post('user/login', 'AuthController@login');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('user/info', 'AuthController@me');
        Route::post('user/logout', 'AuthController@logout');
    });
});

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */
