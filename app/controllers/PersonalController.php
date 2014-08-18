<?php

class PersonalController extends BaseController {

	public function personalInfo(){
		if(!Session::has('user'))
			return "<script>something wrong</script>".Redirect::to('/');
		/* classList */
		$result = DB::table('classList')->get();
		$className = array();
		$i = 0;
		foreach($result as $tmpClass){
			$className[++$i] = $tmpClass->name;
			$className[$tmpClass->name] = $tmpClass->id;
		}
		/*************/
		/* 借用教室查詢 */
		$date = date("Y-m-d");
		$limit = DB::table('BorrowList') 
					->where('date', '>=', $date)
					->where('username', Session::get('username'))
					->orderBy('date') ->orderBy('classroom') ->orderBy('start_time');
		$limit = $limit->get();
		foreach($limit as $tmp){
			$tmp->classroom=$className[$tmp->classroom];
			if($tmp->key == 1) $tmp->key='未借用';
			else if($tmp->key==2) $tmp->key='借出';
			else if($tmp->key==3) $tmp->key='歸還';
			else $tmp->key='未知';
		}
		/****************/
		/* 使用者種類查詢 */
		$student = null;
		$Lab = DB::table('userList')
					->where('userid', Session::get('user'))
					->get();
		if($Lab) $Lab = true;
		else{
			$Lab = false;
			$student = DB::table('StudentCard')
						->where('student_id', Session::get('user'))
						->first();
		}
		/******************/
		return View::make('pages.personalInfo')
					->with('Lab', $Lab)
					->with('student', $student)
					->with('list', $limit);			//查詢的資料
	}

	public function changePW(){
		if(!Session::has('user'))
			return "something wrong";
		$old = htmlspecialchars( Input::get('old') );
		$new1 = md5( htmlspecialchars( Input::get('new1') ) );
		$new2 = md5( htmlspecialchars( Input::get('new2') ) );
		$user = Session::get('user');
		if($new1 != $new2)
			return "新密碼不符";
		$correct = DB::table('userList')
					->where('userid', $user)
					->first()
					->password;
		if($correct != md5($old) )
			return "舊密碼錯誤";
		$update = DB::table('userList')
					->where('userid', $user)
					->update(array('password'=> $new1) );
		return 1;
	}

}
