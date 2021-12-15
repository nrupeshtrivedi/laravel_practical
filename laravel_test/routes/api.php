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

Route::get('users', "Api\UserController@getAllUser"); // List Users
Route::post('users', "Api\UserController@createUser"); // Create User
Route::get('users/{id}', "Api\UserController@getUser"); // Detail of User
Route::put('users/{id}', "Api\UserController@updateUser"); // Update User
Route::delete('users/{id}', "Api\UserController@deleteUser"); // Delete User
Route::post('login', "Api\UserController@login"); // Login User