<?php

class LoginController extends BaseController {

	public function login(){
		
		$passwd = htmlspecialchars( Input::get('pw') );
		$user = htmlspecialchars( Input::get('studentid') );
		if($user == "admin" && md5($passwd)=="1080ff5e02a6b1b0292325f0e7eae8ec"){
			Session::put('user', 'admin');
			return Redirect::to('/');
		}
		$inTable = DB::table('userList')->where('userid', $user)->first()->password;
		if($inTable){
			if($inTable==md5($passwd)){
				Session::put('user', $user);
				return Redirect::to('/');
			}
			else
				return '<script>alert("Wrong Password");</script>'.Redirect::to('Login');
		}
		$link = @imap_open("{mail.ncku.edu.tw:143/novalidate-cert}", $user, $passwd);
	//	or die('Cannot connect to Friggin Server: ' . print_r(imap_errors()));
//		imap_errors();
//		imap_alerts();
		if($link){
//			imap_close($link); //Close the connection
			Session::put('user', strtoupper($user));
			return Redirect::to('/');
		}
		else{
//			imap_close($link);
			return '<script>alert("Wrong Password");</script>'.Redirect::to('Login');
		}

	}

	public function logout(){
		if (Session::has('user')){
			Session::forget('user');
			return '<script>alert("Logout success!!");</script>'.Redirect::to('/');
		}
		else
			return Redirect::to('/');
	}

}

