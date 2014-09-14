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

Route::post('addRepeatDate', 'ClassController@addRepeatDate');

/* StudentCard */

Route::get('DownloadCSV', function(){
	return View::make('pages.DownloadCSV');
});

Route::get('form', 'AdminCardController@check');
Route::get('form_admin/{dept}/{year}/{id}', function($dept, $year, $id){
	if(Session::get('user')!='admin')
		return "<script>something wrong</script>".Redirect::to('/');
	else{
		Session::put('dept',$dept);
		Session::put('year',$year);
		Session::put('id',$id);
		$user = DB::table('StudentCard')
					->where('student_id',$id)
					->get();
		return View::make('pages.formCard')->with('id',$id)->with('student',$user[0]);
	}
});

Route::get('query', 'AdminCardController@query');
Route::get('addnew', 'AdminCardController@addnew');
Route::get('queryCard', 'AdminCardController@queryCard');
Route::get('queryName', 'AdminCardController@queryName');
Route::get('blockList', 'AdminCardController@queryBlock');

Route::post('blockState', 'AdminCardController@blockState');

Route::post('Modify', 'AdminCardController@Modify');

Route::get('form_delete/{dept}/{year}/{id}', function($dept, $year, $id){
	DB::table('StudentCard')->where('student_id', $id)->delete();
	return "<script>alert('finished');</script>".Redirect::to('query?dept='.$dept.'&year='.$year);
});

/***************/

/* personal */

Route::get('Personal', 'PersonalController@personalInfo');

Route::post('changePW', 'PersonalController@changePW');

/************/

/* admin */

Route::get('Admin/{date?}/{date2?}/{factor?}/{detail?}', 'AdminController@show');

Route::get('adminKey/{date?}/{date2?}/{factor?}/{detail?}', 'AdminController@adminKey');
Route::post('keyState', 'AdminController@keyState');
Route::get('allnoKey', 'AdminController@allnoKey');

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

