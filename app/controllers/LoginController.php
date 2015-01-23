<?php

class LoginController extends BaseController {

	public function login(){
		
		$passwd = htmlspecialchars( Input::get('pw') );
		$user = htmlspecialchars( Input::get('studentid') );
//		if($user == "admin" && md5($passwd)=="1080ff5e02a6b1b0292325f0e7eae8ec"){
//			Session::put('user', 'admin');
//			Session::put('username', '最高管理者');
//			return Redirect::to('/');
//		}
		$inTable = DB::table('userList')->where('userid', $user)->first();
		if($inTable && $inTable->password){
			if($inTable->password==md5($passwd)){
				Session::put('user', $user);
				Session::put('username', $inTable->username);
				Session::put('permission', $inTable->permission);
				return Redirect::to('/');
			}
			else
				return '<script>alert("Wrong Password");</script>'.Redirect::to('Login');
		}
		/* 大學部部能登入 */
		if($user[0]!='P' && $user[1]!='7')
				return '<script>alert("目前大學部無法使用，詳情請洽系辦");</script>'.Redirect::to('Login');
		$link = @imap_open("{mail.ncku.edu.tw:143/novalidate-cert}", $user, $passwd);
	//	or die('Cannot connect to Friggin Server: ' . print_r(imap_errors()));
//		imap_errors();
//		imap_alerts();
		if($link)  //成功入口登入成功
		{ 
//			imap_close($link); //Close the connection
			$user = strtoupper($user);
			$inTable = DB::table('StudentCard')->where('student_id', $user)->first();
			if($inTable && $inTable->block){
				return "<script>alert('您無法借用鑰匙，請聯絡系辦，謝謝');</script>".Redirect::to('/');
			}
			else if($inTable && $inTable->name){
				Session::put('user', $user);
				Session::put('username', $inTable->name);
				Session::put('permission', '000001');
				return Redirect::to('/');
			}
			else{
				Session::put('new', $user);
				return "<script>alert('請先填寫個人資料以利系辦審查');</script>".Redirect::to('form');
			}
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
			Session::forget('permission');
			return '<script>alert("Logout success!!");</script>'.Redirect::to('/');
		}
		else
			return Redirect::to('/');
	}

}

