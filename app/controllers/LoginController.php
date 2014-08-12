<?php

class LoginController extends BaseController {

	public function login(){
		
		$passwd = htmlspecialchars( Input::get('pw') );
		$user = htmlspecialchars( Input::get('studentid') );
		if($user == "admin" && md5($passwd)=="1080ff5e02a6b1b0292325f0e7eae8ec"){
			Session::put('user', 'admin');
			Session::put('username', '最高管理者');
			return Redirect::to('/');
		}
		$inTable = DB::table('userList')->where('userid', $user)->first();
		if($inTable && $inTable->password){
			if($inTable->password==md5($passwd)){
				Session::put('user', $user);
				Session::put('username', $inTable->username);
				return Redirect::to('/');
			}
			else
				return '<script>alert("Wrong Password");</script>'.Redirect::to('Login');
		}
		$link = @imap_open("{mail.ncku.edu.tw:143/novalidate-cert}", $user, $passwd);
	//	or die('Cannot connect to Friggin Server: ' . print_r(imap_errors()));
//		imap_errors();
//		imap_alerts();
		if($link)  //成功入口登入成功
		{ 
//			imap_close($link); //Close the connection
			$user = strtoupper($user);
			$inTable = DB::table('StudentCard')->where('student_id', $user)->first();
			if($inTable && $inTable->name){
				Session::put('user', $user);
				Session::put('username', $inTable->name);
				return Redirect::to('/');
			}
			else
				return "<script>alert('請先至學生證管理系統登記個人資料');</script>".Redirect::to('Login');
		}
		else{
//			imap_close($link);
			return '<script>alert("Wrong Password");</script>'.Redirect::to('Login');
		}

	}

	public function logout(){
		if (Session::has('user')){
			Session::forget('user');
			Session::forget('username');
			return '<script>alert("Logout success!!");</script>'.Redirect::to('/');
		}
		else
			return Redirect::to('/');
	}

}

