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

Route::match(array('POST', 'GET'), 'modifyForm/{year}/{month}/{day}/{old?}/{repeat?}', 'ClassController@classForm');

Route::post('borrow', 'ClassController@Borrow');

Route::get('Delete/{_id}/{repeatId?}', 'ClassController@deleteBorrow');

Route::get('Repeat/{_id}', 'ClassController@repeatQuery');

/* admin */

Route::get('Admin/{date?}/{date2?}/{factor?}/{detail?}', 'AdminController@show');

Route::post('adminDate', 'AdminController@updateDate');

Route::get('adminSetting', 'AdminController@adminSetting');

Route::get('adminSettingType/{old?}', 'AdminController@adminSettingType');
Route::match(array('POST', 'GET'), 'SettingType/{id?}', 'AdminController@SettingType');
Route::get('DeleteType/{id}', 'AdminController@DeleteType');

Route::get('adminSettingClassroom/{old?}', 'AdminController@adminSettingClassroom');
Route::match(array('POST', 'GET'), 'SettingClassroom/{id?}', 'AdminController@SettingClassroom');
Route::get('DeleteClassroom/{id}', 'AdminController@DeleteClassroom');

/*********/

/* login & logout */

Route::get('Login', function(){
	return View::make('pages.login');
});

Route::post('log_in', 'LoginController@login');

Route::get('Logout', 'LoginController@logout');

/******************/

