<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//CRUD
Route::post('/create/project','ProjectsController@createProject');

Route::delete('/projects/{id}','ProjectsController@deleteProject');
Route::get('/project/{id}','ProjectsController@getProjectById');

//Sigue Auth
Route::group(['middleware'=>['jwt.auth'],'prefix'=>'v1'],function(){
    Route::get('/projects','ProjectsController@getProjects'); 
    Route::get('/auth/user', 'TokensController@getUserAuthenticated');   
});
Route::group(['middleware'=>[]],function(){
    Route::post('/auth/login', 'TokensController@login');
    Route::post('/auth/refresh', 'TokensController@refreshToken');
    Route::get('/auth/expire', 'TokensController@logout');

});