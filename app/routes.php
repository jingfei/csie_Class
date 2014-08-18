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
	/* classList */
	$result = DB::table('classList')->get();
	$className = array();
	$i = 0;
	foreach($result as $tmpClass)
		$className[++$i] = $tmpClass->name;
	/*************/
	$key = DB::table('BorrowList') 
			->select(array('date', 'classroom', 'username'))
			->where('key', 2)
			->orderBy('date')
			->orderBy('classroom')
			->orderBy('start_time')
			->get();
	foreach($key as $item) $item->classroom = $className[$item->classroom];
	return View::make('pages.home')
			->with('dateLimit', $dateLimit)
			->with('key', $key);
});

Route::get('class/{year?}/{month?}/{day?}', 'ClassController@getClass');

Route::match(array('POST', 'GET'), 'modifyForm/{year}/{month}/{day}/{old?}/{repeat?}', 'ClassController@classForm');

Route::post('borrow', 'ClassController@Borrow');

Route::get('Delete/{_id}/{repeatId?}', 'ClassController@deleteBorrow');

Route::get('Repeat/{_id}', 'ClassController@repeatQuery');

/* personal */

Route::get('Personal', 'PersonalController@personalInfo');

Route::post('changePW', 'PersonalController@changePW');

/************/

/* admin */

Route::get('Admin/{date?}/{date2?}/{factor?}/{detail?}', 'AdminController@show');

Route::get('adminKey/{date?}/{date2?}/{factor?}/{detail?}', 'AdminController@adminKey');
Route::post('keyState', 'AdminController@keyState');

Route::post('adminDate', 'AdminController@updateDate');

Route::get('adminSetting', 'AdminController@adminSetting');

Route::get('adminUser', 'AdminController@adminUser');
Route::get('adminSettingUser/{old?}', 'AdminController@adminSettingUser');
Route::match(array('POST', 'GET'), 'SettingUser/{id?}', 'AdminController@SettingUser');
Route::get('DeleteUser/{id}', 'AdminController@DeleteUser');
Route::get('ResetUser/{id}', 'AdminController@ResetUser');

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

