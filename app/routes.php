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
//	return View::make('index');
	return View::make('pages.home');
});

Route::get('class', function(){
	$data = ClassController::FindClass();
	$year = Input::get('year');
	$month = Input::get('month');
	$day = Input::get('day');
	$date = ClassController::FindDate($month, $day, $year);
	$table = ClassController::SetTable($date, count($data));
	return View::make('pages.class')->with('data',$data)->with('table',$table)->with('date', $date)->with('month', $month)->with('year', $year)->with('day', $day);
});

Route::get('Login', function(){
	return View::make('pages.login');
});

Route::get('/', function()
{
//	return View::make('index');
	return View::make('pages.home');
});

Route::post('log_in', 'LoginController@login');

Route::get('form', 'LoginController@check');
Route::get('form_admin/{dept}/{year}/{id}', function($dept, $year, $id){
	if(Session::get('user')!='admin')
		return "<script>something wrong</script>".Redirect::to('form');
	else{
		Session::put('dept',$dept);
		Session::put('year',$year);
		Session::put('id',$id);
		$user = DB::table('StudentCard')
					->where('student_id',$id)
					->get();
		return View::make('pages.form')->with('id',$id)->with('student',$user[0]);
	}
});

Route::get('query', 'AdminController@query');
Route::get('addnew', 'AdminController@addnew');
Route::get('queryCard', 'AdminController@queryCard');

Route::get('Logout', 'LoginController@logout');

Route::post('Modify', 'LoginController@Modify');

Route::get('form_delete/{dept}/{year}/{id}', function($dept, $year, $id){
	DB::table('StudentCard')->where('student_id', $id)->delete();
	return "<script>alert('finished');</script>".Redirect::to('query?dept='.$dept.'&year='.$year);
});

