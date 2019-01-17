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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::post('createsudoku', 'SudokuController@store');
    Route::post('validate', 'SolveController@store')->middleware('alreadysolved');
    Route::get('sudokus', 'SudokuController@indexNotSolved');
    Route::get('sudokus/{id}', 'SudokuController@show')->where('id','[0-9]+')->middleware('alreadysolved');;
    Route::get('solves', 'SolveController@indexCount');
});


// Routes for unauthentificated users.
Route::group(['middleware' => 'guest:api'], function () {
    // registration route
    Route::post('register', 'Auth\RegisterController@register');
    //login route
    Route::post('login', 'Auth\LoginController@login')->name('login');
});
