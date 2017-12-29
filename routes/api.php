<?php

use App\Http\Resources\UserResource;
use App\Models\User;
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

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function() {
	Route::resources([
		'users' => 'UserController',
		'accounts' => 'AccountController',
	]);
	Route::put('accounts/{id}/default', 'AccountController@makeDefault');
	Route::post('accounts/add', 'AccountController@addMoreAccount');
});
