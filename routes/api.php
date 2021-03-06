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

Route::group(['middleware' => ['jwt.auth']], function () {
  
    // all routes to protected resources are registered here  
    Route::get('users/list', function(){
        $users = App\User::all();
        
        $response = ['success'=>true, 'data'=>$users];
        return response()->json($response, 201);
    });

    /**
     * Logout
     */
    Route::post('user/logout', 'UserController@logout');
});


Route::group(['middleware' => ['jwt.auth']], function(){
    Route::post('job-applicaion/create', 'JobApplicationController@store');
    Route::post('job-applicaion/update', 'JobApplicationController@update');
    Route::post('job-applicaion/delete', 'JobApplicationController@destroy');
    Route::get('job-applicaion/show-all', 'JobApplicationController@showApps');
});

Route::post('user/login', 'UserController@login');
Route::post('user/register', 'UserController@register');