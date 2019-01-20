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
  //logout route
  Route::post('logout', 'Auth\LoginController@logout');

  // get list of yet unsolved sudokus
  Route::get('sudokus', 'SudokuController@indexNotSolved');
  //add a sudoku
  Route::post('sudokus', 'SudokuController@store');
  //delete a sudoku
  Route::delete('sudokus/{id}', 'SudokuController@destroy')->where('id','[0-9]+')->middleware('ownsudoku'); // allowed if user owns the sudoku
  // get the users sudokus
  Route::get('user/sudokus', 'SudokuController@indexUser');
  // get one specific sudoku
  Route::get('sudokus/{id}', 'SudokuController@show')->where('id','[0-9]+')->middleware('alreadysolved');

  //validate a solution
  Route::post('validate', 'SolveController@store')->middleware('alreadysolved'); // protected to avoir solve of multiple sudokus
  // get the answer of a specific sudoku
  Route::get('answer/{id}', 'SudokuController@solve')->where('id','[0-9]+');
  // get the solves count of all users for ranking
  Route::get('solves', 'SolveController@indexCount');
});


// Routes for unauthentificated users.
Route::group(['middleware' => 'guest:api'], function () {
    // registration route
    Route::post('register', 'Auth\RegisterController@register');
    //login route
    Route::post('login', 'Auth\LoginController@login')->name('login');
});
