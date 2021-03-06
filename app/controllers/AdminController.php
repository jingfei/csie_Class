<?php

class AdminController extends BaseController {

	public function show($date=null, $date2=null, $Class=0, $User=null){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('/');
		$dateLimit = self::dateLimit();
		/* check special chars */
		if($date) $date = htmlspecialchars($date, ENT_QUOTES);
		if($date2) $date2 = htmlspecialchars($date2, ENT_QUOTES);
		if($Class) $Class = htmlspecialchars($Class, ENT_QUOTES);
		if($User) $User = htmlspecialchars($User, ENT_QUOTES);
		/***********************/
		/* classList */
		$result = DB::table('classList')->get();
		$className = array();
		$i = 0;
		foreach($result as $tmpClass){
			$className[++$i] = $tmpClass->name;
			$className[$tmpClass->name] = $tmpClass->id;
		}
		/*************/
		/* 課程異動查詢 */
		if(!$date) $date = date("Y-m-d");
		if(!$date2) $date2 = $date;
		$limit = DB::table('BorrowList') 
					->whereBetween('date', array($date, $date2))
					->orderBy('date') ->orderBy('classroom') ->orderBy('start_time');
		if($Class && $Class!=0)
			$limit = $limit->where('classroom', $className[$Class]);
		if($User)
			$limit = $limit->where('username', $User);
		$limit = $limit->get();
		foreach($limit as $tmp){
			$tmp->classroom=$className[$tmp->classroom];
			if($tmp->key == 1) $tmp->key='未借用';
			else if($tmp->key==2) $tmp->key='借出';
			else if($tmp->key==3) $tmp->key='歸還';
			else $tmp->key='未知';
		}
		/****************/
		return View::make('pages.admin')
					->with('date', $dateLimit)		//系統開放時間
					->with('list', $limit)			//查詢的資料
					->with('date1', self::eachDate($date))	//查詢起始日期
					->with('date2', self::eachDate($date2))	//查詢終止日期
					->with('Class', $Class)			//查詢教室
					->with('User', $User);			//查詢使用者
	}

	public function adminKey($date=null, $date2=null, $Class=0, $User=null){
		if(Session::get('permission')[1]!='1')
			return "<script>something wrong</script>".Redirect::to('/');
		$dateLimit = self::dateLimit();
		/* check special chars */
		if($date) $date = htmlspecialchars($date, ENT_QUOTES);
		if($date2) $date2 = htmlspecialchars($date2, ENT_QUOTES);
		if($Class) $Class = htmlspecialchars($Class, ENT_QUOTES);
		if($User) $User = htmlspecialchars($User, ENT_QUOTES);
		/***********************/
		/* classList */
		$result = DB::table('classList')->get();
		$className = array();
		$i = 0;
		foreach($result as $tmpClass){
			$className[++$i] = $tmpClass->name;
			$className[$tmpClass->name] = $tmpClass->id;
		}
		/*************/
		/* 課程異動查詢 */
		if(!$date) $date = date("Y-m-d");
		if(!$date2) $date2 = $date;
		//list1
		$limit = DB::table('BorrowList') 
					->whereBetween('date', array($date, $date2))
					->orderBy('date') 
					->orderBy('classroom') 
					->orderBy('start_time');
		if($Class && $Class!=0)
			$limit = $limit->where('classroom', $className[$Class]);
		if($User)
			$limit = $limit->where('username', $User);
		$list1 = $limit->where('key', 1)->get();
		//list2
		$limit = DB::table('BorrowList') 
					->whereBetween('date', array($date, $date2))
					->orderBy('date') 
					->orderBy('classroom') 
					->orderBy('start_time');
		if($Class && $Class!=0)
			$limit = $limit->where('classroom', $className[$Class]);
		if($User)
			$limit = $limit->where('username', $User);
		$list2 = $limit->where('key', 2)->get();
		//list3
		$limit = DB::table('BorrowList') 
					->whereBetween('date', array($date, $date2))
					->orderBy('date') 
					->orderBy('classroom') 
					->orderBy('start_time');
		if($Class && $Class!=0)
			$limit = $limit->where('classroom', $className[$Class]);
		if($User)
			$limit = $limit->where('username', $User);
		$list3 = $limit->where('key', 3)->get();
		foreach($list1 as $tmp)
			$tmp->classroom=$className[$tmp->classroom];
		foreach($list2 as $tmp)
			$tmp->classroom=$className[$tmp->classroom];
		foreach($list3 as $tmp)
			$tmp->classroom=$className[$tmp->classroom];
		/****************/
		return View::make('pages.adminKey')
					->with('list1', $list1)			//未借用的資料
					->with('list2', $list2)			//借出的資料
					->with('list3', $list3)			//歸還的資料
					->with('date1', self::eachDate($date))	//查詢起始日期
					->with('date2', self::eachDate($date2))	//查詢終止日期
					->with('Class', $Class)			//查詢教室
					->with('User', $User);			//查詢使用者
	}

	public function allnoKey(){
		if(Session::get('permission')[1]!='1')
			return "<script>something wrong</script>".Redirect::to('/');
		$dateLimit = self::dateLimit();
		/* classList */
		$result = DB::table('classList')->get();
		$className = array();
		$i = 0;
		foreach($result as $tmpClass){
			$className[++$i] = $tmpClass->name;
			$className[$tmpClass->name] = $tmpClass->id;
		}
		/*************/
		/* 未歸還鑰匙查詢 */
		$limit = DB::table('BorrowList') 
					->where('key', 2)
					->orderBy('date') 
					->orderBy('classroom') 
					->orderBy('start_time')
					->get();
		foreach($limit as $tmp)
			$tmp->classroom=$className[$tmp->classroom];
		/****************/
		return View::make('pages.adminKey')
					->with('list1', null)			//未借用的資料
					->with('list2', $limit)			//借出的資料
					->with('list3', null)			//歸還的資料
					->with('date1', null)	//查詢起始日期
					->with('date2', null)	//查詢終止日期
					->with('Class', null)			//查詢教室
					->with('User', null);			//查詢使用者
	}

	public function keyState(){
		if(Session::get('permission')[1]!='1')
			return "<script>something wrong</script>".Redirect::to('/');
		$id = htmlspecialchars( Input::get('id'), ENT_QUOTES );
		$state = htmlspecialchars( Input::get('state'), ENT_QUOTES );
		DB::table('BorrowList')
			->where('id', $id)
			->update(array('key' => $state));
	}

	public function updateDate(){
		if(Session::get('user')!='admin')
			return "something wrong";
		$Choose = htmlspecialchars(Input::get('Open'), ENT_QUOTES);
		if($Choose=="no"){
			$update1 = DB::table('Admin')
						 ->where('name', 'date_start')
						 ->update(array('detail' => '-'));
			$update2 = DB::table('Admin')
			  			 ->where('name', 'date_end')
		  				 ->update(array('detail' => '-'));
			return "系統目前狀態為不開放借用";
		}
		else{
			$month1 = htmlspecialchars(Input::get('month1'), ENT_QUOTES);
			$month2 = htmlspecialchars(Input::get('month2'), ENT_QUOTES);
			$day1 = htmlspecialchars(Input::get('day1'), ENT_QUOTES);
			$day2 = htmlspecialchars(Input::get('day2'), ENT_QUOTES);
			$year1 = htmlspecialchars(Input::get('year1'), ENT_QUOTES);
			$year2 = htmlspecialchars(Input::get('year2'), ENT_QUOTES);
			if( !(is_numeric($month1) && is_numeric($month2) && is_numeric($day1) && is_numeric($day2) && is_numeric($year1) && is_numeric($year2)) )
				return "日期錯誤";
			$date1 = date("Y-m-d", mktime(0,0,0,$month1,$day1,$year1));
			$date2 = date("Y-m-d", mktime(0,0,0,$month2,$day2,$year2));
			if($date1 > $date2) 
				return "日期錯誤";
			else{
				try{
					$update1 = DB::table('Admin')
								 ->where('name', 'date_start')
								 ->update(array('detail' => $date1));
					$update2 = DB::table('Admin')
					  			 ->where('name', 'date_end')
				  				 ->update(array('detail' => $date2));
				}catch(\Exception $e){
					return "更新失敗";
				}
				return "更新成功";
			}
		}
	}

	public function adminSetting(){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		/* classList */
		$limit = DB::table('classList')->get();
		$reason = DB::table('typeList')->get();
		$announce = DB::table('announceList')->get();
		return View::make('pages.adminSetting')
					->with('reason', $reason)
					->with('list', $limit)
					->with('announce',$announce);
	}

	public function adminUser(){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		/* userList */
		$limit = DB::table('userList')->get();
		return View::make('pages.adminUser')
					->with('list', $limit);
	}

	public function adminSettingUser($old=null){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$old = htmlspecialchars($old, ENT_QUOTES);
		$url = "SettingUser";
		if($old){
			$old = DB::table('userList')->where('id', $old)->first();
			$url .= "/".$old->id;
		}
		return View::make('pages.adminUserForm')
					->with('old', $old)
					->with('Url', $url);
	}

	public function ResetUser($id){
		if(Session::get('user')!='admin' || $id==0)
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$id = htmlspecialchars($id, ENT_QUOTES);
		DB::table('userList')->where('id', $id)->update(array('password'=> md5('csie')));
		return "<script>alert('密碼已重設為csie');</script>".Redirect::to('adminUser');
	}

	public function DeleteUser($id){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$id = htmlspecialchars($id, ENT_QUOTES);
		DB::table('userList')->where('id', $id)->delete();
		return "<script>alert('刪除成功');</script>".Redirect::to('adminUser');
	}

	public function SettingUser($id=null){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$id = htmlspecialchars($id, ENT_QUOTES);
		$userid = htmlspecialchars( Input::get('userid'), ENT_QUOTES );
		$username = htmlspecialchars( Input::get('username'), ENT_QUOTES );
		$ar = array();
		$ar['userid'] = $userid;
		$ar['username'] = $username;
		if($id)
			DB::table('userList')->where('id', $id)->update($ar);
		else{
			$ar['password'] = '1575b02452c2682e6f79db042a18a78d';
			DB::table('userList')->insert($ar);
		}
		return "<script>alert('更新成功');</script>".Redirect::to('adminUser');
	}

	public function adminSettingType($old=null){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$old = htmlspecialchars($old, ENT_QUOTES);
		$url = "SettingType";
		if($old){
			$old = DB::table('typeList')->where('id', $old)->first();
			$url .= "/".$old->id;
		}
		return View::make('pages.adminTypeForm')
					->with('old', $old)
					->with('Url', $url);
	}

	public function DeleteType($id){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$id = htmlspecialchars($id, ENT_QUOTES);
		DB::table('typeList')->where('id', $id)->delete();
		return "<script>alert('刪除成功');</script>".Redirect::to('adminSetting');
	}

	public function SettingType($id=null){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$id = htmlspecialchars($id, ENT_QUOTES);
		$name = htmlspecialchars( Input::get('name'), ENT_QUOTES );
		$color = htmlspecialchars( Input::get('color'), ENT_QUOTES );
		$ar = array();
		$ar['type'] = $name;
		$ar['color'] = $color;
		if($id)
			DB::table('typeList')->where('id', $id)->update($ar);
		else
			DB::table('typeList')->insert($ar);
		return "<script>alert('更新成功');</script>".Redirect::to('adminSetting');
	}

	public function adminSettingAnnounce(){
		if(Session::get('user')!='admin')
			return "please login as admin";
		$id = htmlspecialchars(Input::get('id'), ENT_QUOTES);
		$content = Input::get('content');
		$ar = array();
		$ar['date'] = date('Y-m-d');
		$ar['content'] = $content;
		if($id)
			DB::table('announceList')->where('id', $id)->update($ar);
		else
			DB::table('announceList')->insert($ar);
		return "更新成功";
		
	}

	public function DeleteAnnounce($id){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$id = htmlspecialchars($id, ENT_QUOTES);
		DB::table('announceList')->where('id', $id)->delete();
		return "<script>alert('刪除成功');</script>".Redirect::to('adminSetting');
	}

	public function adminSettingClassroom($old=null){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$old = htmlspecialchars($old, ENT_QUOTES);
		$url = "SettingClassroom";
		if($old){
			$old = DB::table('classList')->where('id', $old)->first();
			$url .= "/".$old->id;
		}
		return View::make('pages.adminClassroomForm')
					->with('old', $old)
					->with('Url', $url);
	}

	public function DeleteClassroom($id){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$id = htmlspecialchars($id, ENT_QUOTES);
		DB::table('classList')->where('id', $id)->delete();
		return "<script>alert('刪除成功');</script>".Redirect::to('adminSetting');
	}

	public function SettingClassroom($id=null){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$id = htmlspecialchars($id, ENT_QUOTES);
		$name = htmlspecialchars( Input::get('name'), ENT_QUOTES );
		$type = htmlspecialchars( Input::get('type'), ENT_QUOTES );
		$capacity = htmlspecialchars( Input::get('capacity'), ENT_QUOTES );
		$ar = array();
		$ar['name'] = $name;
		$ar['type'] = $type;
		$ar['capacity'] = $capacity;
		if($id)
			DB::table('classList')->where('id', $id)->update($ar);
		else
			DB::table('classList')->insert($ar);
		return "<script>alert('更新成功');</script>".Redirect::to('adminSetting');
	}

	public function csvData(){
		$data = self::FindClass();
		return View::make('pages.csvData')
					->with('data', $data);	
	}

}
