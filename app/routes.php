<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	$dateLimit = BaseController::dateLimit();
	return View::make('pages.home')->with('dateLimit', $dateLimit);
});

Route::get('class/{year?}/{month?}/{day?}', 'ClassController@getClass');

Route::post('modifyForm/{year}/{month}/{day}', 'ClassController@classForm');

Route::post('borrow', 'ClassController@Borrow');

/* admin */

Route::get('Admin/{date?}/{date2?}/{factor?}/{detail?}', 'AdminController@show');
Route::get('AdminDelete/{_id}', 'AdminController@deleteBorrow');

Route::post('adminDate', 'AdminController@updateDate');

/*********/

/* login & logout */

Route::get('Login', function(){
	return View::make('pages.login');
});

Route::post('log_in', 'LoginController@login');

Route::get('Logout', 'LoginController@logout');

/******************/

