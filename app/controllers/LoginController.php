<?php

class LoginController extends BaseController {

	public function login(){
		
		$passwd = htmlspecialchars( Input::get('pw') );
		$user = htmlspecialchars( Input::get('studentid') );
		if($user == "admin" && md5($passwd)=="1080ff5e02a6b1b0292325f0e7eae8ec"){
			Session::put('user', 'admin');
			return Redirect::to('query');
		}
		$link = @imap_open("{mail.ncku.edu.tw:143/novalidate-cert}", $user, $passwd);
	//	or die('Cannot connect to Friggin Server: ' . print_r(imap_errors()));
//		imap_errors();
//		imap_alerts();
		if($link){
//			imap_close($link); //Close the connection
			Session::put('user', strtoupper($user));
			return Redirect::to('form');
		}
		else{
//			imap_close($link);
			return '<script>alert("Wrong Password");</script>'.Redirect::to('/');
		}

	}

	public function check(){
		if (Session::has('user')){
			if(Session::get('user')=='admin')
				return Redirect::to('query');
			else{
				$id = Session::get('user');
				$user = DB::table('StudentCard')
							->where('student_id',$id)
							->get();
				return View::make('pages.form')->with('id',$id)->with('student',$user?$user[0]:null);
			}
		}
		else
			return Redirect::to('/');
	}

	public function logout(){
		if (Session::has('user')){
			Session::forget('user');
			return '<script>alert("Logout success!!");</script>'.Redirect::to('/');
		}
		else
			return Redirect::to('/');
	}

	public function Modify(){
		$StudentID = Session::get('user');
		if($StudentID=='admin')
			$StudentID = Session::get('id');
		$user = DB::table('StudentCard')
					->where('student_id',$StudentID)
					->get();
		$name = htmlspecialchars(Input::get('name'));
		$number =  preg_replace('/\D/','',htmlspecialchars(Input::get('number')));
		$department = htmlspecialchars(Input::get('dept'));
		$en_year = htmlspecialchars(Input::get('year'));
		$card = htmlspecialchars(Input::get('card'));
		if($user){
			$user = $user[0];
			DB::table('StudentCard')
				->where('student_id',$StudentID)
				->update(array( 'student_id'=> $StudentID,
								'name' => $name,
								'department' => $department,
								'enrollment_year' => $en_year,
								'student_card' => $card,
								'phone' => $number
							  )
						);
		}
		else{
			DB::table('StudentCard')
				->insert(array( 'student_id' => $StudentID,
								'name' => $name,
								'department' => $department,
								'enrollment_year' => $en_year,
								'student_card' => $card,
								'phone' => $number
							  )
						);
		}
		if(Session::get('user')=='admin'){
			$dept = Session::get('dept');
			$year = Session::get('year');
			return "<script>alert('finished');</script>".Redirect::to('query?dept='.$dept.'&year='.$year);
		}
		else{
			Session::forget('user');
			return "<script>alert('finished');</script>".Redirect::to('/');
		}
	}

}

