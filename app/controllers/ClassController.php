<?php

class ClassController extends \BaseController {

	private function FindDate($month, $day, $year){
		$date = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$date = DB::table('BorrowList')
				->select('id', 'classroom', 'start_time', 'end_time', 'username', 'reason', 'repeat', 'type')
				->where('date', $date)
				->orderBy('classroom')
				->orderBy('start_time')
				->get();
		return $date;
	}

	private function SetTable($date, $count){
		/*initialize array*/
		$tmp = array();
		for($i=0; $i<=$count; $i++) array_push($tmp, array(-1,"","") );
		$table = array();
		for($i=8; $i<22; $i++) array_push($table, $tmp);
		/******************/
		/* find Type */
		$result = DB::table('typeList') ->get();
		$typeAr = array();
		foreach($result as $tmp)
			$typeAr[$tmp->id]=$tmp->color;
		/*************/
		foreach($date as $obj){
			$during = $obj->end_time - $obj->start_time;
			$table[$obj->start_time-8][$obj->classroom][0] = $during;
			$table[$obj->start_time-8][$obj->classroom][1] = $obj->reason;
			$table[$obj->start_time-8][$obj->classroom][2] = $obj->username;
			/* 可否編輯/刪除 */
			if(Session::get('user')=='admin')
				$table[$obj->start_time-8][$obj->classroom][3] = $obj->id;
			else if($obj->username==Session::get('username'))
				$table[$obj->start_time-8][$obj->classroom][3] = $obj->id;
			else
				$table[$obj->start_time-8][$obj->classroom][3] = false;
			/*****************/
			$table[$obj->start_time-8][$obj->classroom][4] = $obj->repeat; /* 是否重複 */
			$table[$obj->start_time-8][$obj->classroom][5] = $typeAr[$obj->type]; //type color
			for($i=$obj->start_time-8+1, $j=1; $j<$during; $j++, $i++)
				$table[$i][$obj->classroom][0] = 0;
		}
		return $table;
	}

	public function getClass($year=null, $month=null, $day=null){
		if(Session::has('new'))
			return Redirect::to('form');
		if(!$year) $year=date("Y"); 
		else $year = htmlspecialchars($year, ENT_QUOTES);
		if(!$month) $month=date("m");
		else $month = htmlspecialchars($month, ENT_QUOTES);
		if(!$day) $day=date("d"); 
		else $day = htmlspecialchars($day, ENT_QUOTES);
		$thisDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$dateLimit = self::dateLimit();
		$warning = null;
		if($thisDate>$dateLimit['end']['all'] || $thisDate<$dateLimit['start']['all'])
			$warning = "不開放借用";
		else if($thisDate>'2015-02-17' && $thisDate<'2015-02-23')
			$warning = "不開放借用";
		if(Session::get('user')=='admin') $warning=null;
		$data = self::FindClass();
		$date = self::FindDate($month, $day, $year);
		$table = self::SetTable($date, count($data));
		$type = DB::table('typeList')->get();
		$nowDate = date("Y-m-d");
		$disable = $nowDate > $thisDate; 
		if($warning) $disable = true;
		return View::make('pages.class')
					->with('data',$data)
					->with('table',$table)
					->with('type', $type)
					->with('date', $date)
					->with('month', $month)
					->with('year', $year)
					->with('day', $day)
					->with('warning', $warning)
					->with('disable', $disable);
	}

	public function classForm($year, $month, $day, $old=null, $repeat=null){
		if(!Session::has('user'))
			return "<script>alert('請登入');</script>".Redirect::back();
		/* check special chars */
		$month = htmlspecialchars($month, ENT_QUOTES);
		$day = htmlspecialchars($day, ENT_QUOTES);
		$year = htmlspecialchars($year, ENT_QUOTES);
		if($old) $old = htmlspecialchars($old, ENT_QUOTES);
		if($repeat) $repeat = htmlspecialchars($repeat, ENT_QUOTES);
		/***********************/
		/* 檢查日期是否過了 */
		if(!$old && !$repeat){
			$thisDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
			$nowDate = date("Y-m-d");
			if($thisDate < $nowDate)
				return "<script>alert('日期已過，無法修改');</script>".Redirect::to('class/'.$year.'/'.$month.'/'.$day);
		}
		/********************/
		$diffUser=null;
		/*get username*/
		$User = DB::table('userList')
					->where('userid', Session::get('user'))
					->first();
		if($User)
			$User = $User->username;
		else
			$User = DB::table('StudentCard')
						->where('student_id', Session::get('user'))
						->first()
						->name;
		/**************/
		$result = null;
		if($old || $repeat){
			if($old) $result = DB::table('BorrowList')->where('id', $old)->first();
			else $result = DB::table('BorrowList')->where('repeat', $repeat)->first();
			if(!$result) 
				return "<script>alert('something wrong...');</script>".Redirect::to('/');
			if(!self::CheckUserSession($result->user_id))
				return "<script>alert('something wrong...');</script>".Redirect::to('/');
			$diffUser=$result->username;
			$year = strtok($result->date, '-');
			$month = strtok('-');
			$day = strtok('-');
			/* 檢查日期是否過了 */
			$thisDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
			$nowDate = date("Y-m-d");
			if($thisDate < $nowDate)
				return "<script>alert('日期已過，無法修改');</script>".Redirect::to('class/'.$year.'/'.$month.'/'.$day);
			/********************/
			$className = DB::table('classList')
							->where('id', $result->classroom)
							->first()
							->id;
			$startTime = $result->start_time;
			$endTime = $result->end_time;
		}
		else{
			$className = htmlspecialchars( Input::get('className'), ENT_QUOTES );
			$startTime = htmlspecialchars( Input::get('startTime'), ENT_QUOTES );
			$endTime = htmlspecialchars( Input::get('endTime'), ENT_QUOTES );
		}
		$thisDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		/* 檢查日期 （管理者除外）*/
		$dateLimit = self::dateLimit();
		if(!$repeat && Session::get('user')!='admin' && ($thisDate>$dateLimit['end']['all'] || $thisDate<$dateLimit['start']['all']) )
			return "<script>alert('日期錯誤');</script>".Redirect::to('/');
		/**************************/
		$data = self::FindClass();
		/* reason */
		$reason = DB::table('typeList')->get();
		return View::make('pages.form')
					->with('data',$data)
					->with('reason', $reason)
					->with('month', $month)
					->with('year', $year)
					->with('day', $day)
					->with('user', $diffUser ? $diffUser : $User)
					->with('old', $result)
					->with('className', $className)
					->with('startTime', $startTime)
					->with('endTime', $endTime)
					->with('repeat', $repeat);
	}

	public function deleteBorrow($_id, $repeatId=null){
		$nowdate = date("Y-m-d");
		$_id = htmlspecialchars($_id, ENT_QUOTES);
		if(!$repeatId) $data = DB::table('BorrowList')->where('id', $_id);
		else $data = DB::table('BorrowList')->where('repeat', htmlspecialchars($repeatId), ENT_QUOTES);
		if(!$data)
			return "<script>something wrong</script>".Redirect::to('/');
		/* check ID */
		if(Session::get('user')!='admin' && $data->first()->username!=Session::get('username'))
			return "<script>something wrong</script>".Redirect::to('/');
		/************/
		$date = self::eachDate($data->first()->date);
		if($data->first()->date < $nowdate)
			return "<script>alert('日期已過，無法刪除');</script>".Redirect::to('class/'.$date['year'].'/'.$date['month'].'/'.$date['day']);
		$data->delete();
		return "<script>alert('刪除成功');</script>".Redirect::to('class/'.$date['year'].'/'.$date['month'].'/'.$date['day']);
	}

	public function repeatSplit(){
		$_id = htmlspecialchars( Input::get('_id'), ENT_QUOTES );
		$year = htmlspecialchars( Input::get('year'), ENT_QUOTES );
		$month = htmlspecialchars( Input::get('month'), ENT_QUOTES );
		$day = htmlspecialchars( Input::get('day'), ENT_QUOTES );
		$dateSplit = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$data = DB::table('BorrowList')
					->where('repeat', $_id)
					->where('date', '<', $dateSplit)
					->orderBy('date', 'desc')
					->first();
		if(!$data)
			return $_id;
		$data = $data->date;
		/* check ID */
		if(Session::get('user')!='admin' && $data->first()->username!=Session::get('username'))
			return "<script>something wrong</script>".Redirect::to('/');
		/************/
		$data = DB::table('BorrowList')
					->where('repeat', $_id)
					->where('date', '>', $data)
					->orderBy('date', 'asc')
					->first();
		$firstDate = $data->date;
		$firstID = $data->id;
		$result = DB::table('BorrowList')
					->where('repeat', $_id)
					->where('date', '>=', $firstDate)
					->update(array('repeat' => $firstID));
		return $firstID;
	}

	public function repeatQuery($_id){
		$_id = htmlspecialchars($_id, ENT_QUOTES);
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
		$limit = DB::table('BorrowList')
					->where('repeat', $_id)
					->orderBy('date')
					->get();
		foreach($limit as $tmp){
			$tmp->classroom=$className[$tmp->classroom];
			if($tmp->key == 1) $tmp->key='未借用';
			else if($tmp->key==2) $tmp->key='借出';
			else if($tmp->key==3) $tmp->key='歸還';
			else $tmp->key='未知';
		}
		/* 可否修改 */
		$change = true;
		if(Session::get('user')!='admin' && $limit[0]->username!=Session::get('username'))
			$change = false;
		/************/
		return View::make('pages.repeatQuery')
					->with('change', $change)
					->with('_id',$_id)
					->with('list', $limit);
	}

	public function addRepeatDate(){
		$_id = htmlspecialchars( Input::get('_id'), ENT_QUOTES );
		$year = htmlspecialchars( Input::get('year'), ENT_QUOTES );
		$month = htmlspecialchars( Input::get('month'), ENT_QUOTES );
		$day = htmlspecialchars( Input::get('day'), ENT_QUOTES );
		$date = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$dateLimit = self::dateLimit();
		$limit = DB::table('BorrowList')
					->where('repeat', $_id)
					->first();
		/* 可否修改 */
		if(Session::get('user')!='admin' && $limit->username!=Session::get('username'))
			return "error";
		/************/
		/*檢查日期 (管理者除外) */
		$dateLimit = self::dateLimit();
		if(Session::get('user')!='admin' && ($date>$dateLimit['end']['all'] || $date<$dateLimit['start']['all']) )
			return "日期錯誤";
		/************************/
		/* 檢查日期是否過了 */
		$nowDate = date("Y-m-d");
		if($date < $nowDate)
			return "日期已過，無法修改";
		/********************/
		$result = DB::table('BorrowList')
					->select('start_time', 'end_time')
					->where('date', $date)
					->where('classroom', $limit->classroom)
					->get();
		if(self::CheckRepeat($result,$limit->start_time,$limit->end_time)) //if someone borrow the class first
			return "教室已被借用，請確認後重新借用";
		$ar = $limit;
		$ar->key = 1;
		$ar->id = null;
		$ar->state = 1;
		$ar->date = $date;
		$ar = (array)$ar;
		$result = DB::table('BorrowList')->insert($ar);
		return "更新成功";		
	}

	public function Borrow(){
		if(!Session::has('user'))
			return "<script>alert('請登入');</script>".Redirect::back();
		$user = htmlspecialchars( Input::get('form_user'), ENT_QUOTES );
		$old = htmlspecialchars( Input::get('old'), ENT_QUOTES ); 
		if($old && Session::get('user')!='admin'){ //檢查post ID是否為本人
			$result = DB::table('BorrowList')
						->where('id', $old)
						->first();
			if(!self::CheckUserSession($result->user_id))
				return "<script>alert('something wrong...');</script>".Redirect::to('/');
		}
		$old_repeat = htmlspecialchars( Input::get('old_repeat'), ENT_QUOTES );
		/* 檢查使用者 */
		$userInfo = DB::table('userList')
						->where('username', $user)
						->first();
		if($userInfo){
			$user_id = $userInfo->id;
			$username = $userInfo->username;
			$userid = $userInfo->userid;
		}
		else{
			$userInfo = DB::table('StudentCard')
							->where('student_id', Session::get('user'))
							->first();
			$user_id = $userInfo->id;
			$username = $userInfo->name;
			$userid = $userInfo->student_id;
		}
		if(Session::get('user')!='admin' && $username!=$user) //登入與表單使用者不同
				return "<script>alert('something wrong...');</script>".Redirect::to('/');
		/**************/
		$date_start = htmlspecialchars( Input::get('date_start'), ENT_QUOTES );
		$title = htmlspecialchars( Input::get('title'), ENT_QUOTES );
		$class = htmlspecialchars( Input::get('form_class'), ENT_QUOTES );
		$class = strtok($class, " ");
		$classId = -1;
		$time_start = htmlspecialchars( Input::get('time_start'), ENT_QUOTES );
		$time_end = htmlspecialchars( Input::get('time_end'), ENT_QUOTES );
		if(strlen($time_end)==4) $time_end=(int)$time_end[0];
		else $time_end = (int)($time_end[0].$time_end[1]);
		if(strlen($time_start)==4) $time_start=(int)$time_start[0];
		else $time_start = (int)($time_start[0].$time_start[1]);
		/* 檢查時間 */
		if($time_start >= $time_end)
			return "<script>alert('時間選擇錯誤');</script>".Redirect::to('/');
		/************/
		$email = htmlspecialchars( Input::get('form_email'), ENT_QUOTES );
		$tel = htmlspecialchars( Input::get('form_tel'), ENT_QUOTES );
		$type = htmlspecialchars( Input::get('form_reason'), ENT_QUOTES );
		$typeId = -1;
		/*檢查日期 (管理者除外) */
		$dateLimit = self::dateLimit();
		if(!$old_repeat && Session::get('user')!='admin' && ($date_start>$dateLimit['end']['all'] || $date_start<$dateLimit['start']['all']) )
			return "<script>alert('日期錯誤');</script>".Redirect::to('class/');
		else if(Session::get('user')!='admin' && $date_start>'2015-02-17' && $date_start<'2015-02-23')
			return "<script>alert('日期錯誤');</script>".Redirect::to('class/');
		/************************/
		/* 檢查日期是否過了 */
		$nowDate = date("Y-m-d");
		if($date_start && $date_start < $nowDate)
			return "<script>alert('日期已過，無法修改');</script>".Redirect::to('/');
		/********************/
		/* repeat */
		$Repeat = Input::get('form_repeat') ? true : false;
		if($old) $Repeat=false;
		$interval = 0;
		$intervalUnit = "";
		if($Repeat){
			$interval = Input::get('date_interval');
			$intervalUnit = Input::get('date_intervalUnit');
		}
		$dateWay = 0;
		$dateTmp = "";
		if($Repeat && Input::get('Repeat_end')=='date'){ //循環方式為日期
			$dateWay=1;
			$year = Input::get('date_year');
			$month = Input::get('date_month');
			$day = Input::get('date_day');
			$dateTmp = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		}
		else if($Repeat && Input::get('Repeat_end')=='occurence'){ //次數
			$dateWay = 2;
			$dateTmp = Input::get('date_num');
		}
		/**********/
		/* classList */
		$result = DB::table('classList')->get();
		foreach($result as $tmpClass)
			if($tmpClass->name == $class){
				$classId = $tmpClass->id;
				if($tmpClass->name == "4210" &&
					$date_start>="2015-07-00")
					return "<script>alert('此教室已不開放使用');</script>".Redirect::to('class');
				break;
			}
		if($classId == -1) return "<script>alert('沒有這間教室');</script>".Redirect::to('class');
		/*************/
		/* typeList */
		$result = DB::table('typeList')->get();
		foreach($result as $tmpType)
			if($tmpType->type == $type){
				$typeId = $tmpType->id;
				break;
			}
		if($typeId == -1)
			return "<script>alert('請選擇借用事由');</script>".Redirect::to('class');
		/*************/
		/* data */
		$eachDate = self::eachDate($date_start);
		$ar=array();
		if(!$old_repeat) $ar['date'] = $date_start;
		$ar['classroom'] = $classId;
		$ar['start_time'] = $time_start;
		$ar['end_time'] = $time_end;
		$ar['user_id'] = $user_id;
		$ar['username'] = $username;
		$ar['phone'] = $tel;
		$ar['email'] = $email;
		$ar['reason'] = $title;
		$ar['type'] = $typeId;
		$ar['repeat'] = 0; //若有修改，返回預設
		/********/
		/* check */
		$cannotClass = array();
		$canClass = array();
		if($old_repeat){
			/* 更新連續資料 */
			$dataTmp = DB::table('BorrowList')
						->where('repeat', $old_repeat)
						->get();
			foreach($dataTmp as $tmp){
				$tmp=$tmp->date;
				$result = null;
				if(Session::get('user')=='admin' || $tmp <= $dateLimit['end']['all']) //未超出日期範圍
					$result = DB::table('BorrowList')
								->select('start_time', 'end_time')
								->where('date', $tmp)
								->where('classroom', $classId)
								->whereNotIn('repeat', array($old_repeat))
								->get();
				if(self::CheckRepeat($result,$time_start,$time_end)) //if someone borrow the class first
					array_push($cannotClass, $tmp);
				else
					array_push($canClass, $tmp);
			}
			if(!empty($cannotClass)){
				$warning = "更新失敗\\n";
				foreach($cannotClass as $tmp)
					$warning .= " ".$tmp."\\n";
				$warning .= "以上日期有誤 請檢查";
			}
			else{
				$ar['repeat'] = $old_repeat;
				unset($ar['date']);
				$result = DB::table('BorrowList')
						->where('repeat', $old_repeat)
						->update($ar);
				$warning = "更改成功！！";
			}
			return "<script>alert('$warning');</script>".Redirect::to('Repeat/'.$old_repeat);
		}
		else if(!$Repeat){
			$result = DB::table('BorrowList')
						->where('date', $date_start)
						->where('classroom', $classId);
			if($old) $result->whereNotIn('id', array($old));
			$result = $result->get();
			/* if someone has borrowed the class first */
			if(self::CheckRepeat($result,$time_start,$time_end)){
				$warning = $class."教室已被借出，\\n請確認後重新借用";
				return "<script>alert('$warning');</script>".Redirect::to('class/'.$eachDate['year'].'/'.$eachDate['month'].'/'.$eachDate['day']);
			}
			/*******************************************/
			/* 更新資料 */
			else if($old){  
				$result = DB::table('BorrowList')
							->where('id', $old)
							->update($ar);
				return "<script>alert('更新成功');</script>".Redirect::to('class/'.$eachDate['year'].'/'.$eachDate['month'].'/'.$eachDate['day']);
			}
			/************/
			else{
				$result = DB::table('BorrowList')->insert($ar);
				$warning = $class."教室於".$date_start."   ".$time_start.":00 ~ ".$time_end.":00 成功借用!!";
				return "<script>alert('$warning');</script>".Redirect::to('class/'.$eachDate['year'].'/'.$eachDate['month'].'/'.$eachDate['day']);
			}
		}
		else{  //Repeat
			if($dateWay == 1){ //循環方式為日期
				for( $tmp=$date_start; $tmp<=$dateTmp; $tmp=self::NextDate($intervalUnit, $interval, $tmp) ){
					$result = null;
					if(Session::get('user')=='admin' || $tmp <= $dateLimit['end']['all']) //未超出日期範圍
						$result = DB::table('BorrowList')
									->select('start_time', 'end_time')
									->where('date', $tmp)
									->where('classroom', $classId)
									->get();
					if(self::CheckRepeat($result,$time_start,$time_end)) //if someone borrow the class first
						array_push($cannotClass, $tmp);
					else
						array_push($canClass, $tmp);
				}
			}
			else if($dateWay == 2){ //循環方式為次數
				$tmp=$date_start;
				for($i=0; $i<$dateTmp; ++$i){
					$result = null;
					if(Session::get('user')=='admin' || $tmp <= $dateLimit['end']['all']) //未超出日期範圍
						$result = DB::table('BorrowList')
									->select('start_time', 'end_time')
									->where('date', $tmp)
									->where('classroom', $classId)
									->get();
					if(self::CheckRepeat($result,$time_start,$time_end)) //if someone borrow the class first
						array_push($cannotClass, $tmp);
					else
						array_push($canClass, $tmp);
					$tmp=self::NextDate($intervalUnit, $interval, $tmp);
				}
			}
			$warning = $class."教室";
			if(!empty($cannotClass)){
				$warning .= "於\\n";
				foreach($cannotClass as $tmp)
					$warning .= " ".$tmp."\\n";
				$warning .= "無法借用或借出;\\n\\n";
			}
			if(!empty($canClass)){
				$FirstId = 0;
				$warning .= "於\\n";
				foreach($canClass as $tmp){
					if($FirstId==0){
						$FirstId = DB::table('BorrowList')->insertGetId($ar);
						$ar['repeat']=$FirstId;
						$result = DB::table('BorrowList')
							->where('id', $FirstId)
							->update($ar);
					}
					else{
						$ar['date'] = $tmp;
						$result = DB::table('BorrowList')->insert($ar);
					}
					$warning .= " ".$tmp."\\n";
				}
				$warning .= "成功借用!!";
			}
			else
				$warning .= "借用未成功";
			return "<script>alert('$warning');</script>".Redirect::to('class/'.$eachDate['year'].'/'.$eachDate['month'].'/'.$eachDate['day']);
		}
	}

	public function CheckClass($confirm=0){
		if(Session::get('user')!='admin') 
			return "<script>alert('plz login as administrator');</script>".Redirect::to('/');
		$username = "最高管理者";
		$date_start = htmlspecialchars( Input::get('date_start'), ENT_QUOTES );
		$title = htmlspecialchars( Input::get('title'), ENT_QUOTES );
		$class = htmlspecialchars( Input::get('form_class'), ENT_QUOTES );
		$class = strtok($class, " ");
		$classId = -1;
		$time_start = htmlspecialchars( Input::get('time_start'), ENT_QUOTES );
		$time_end = htmlspecialchars( Input::get('time_end'), ENT_QUOTES );
		if(strlen($time_end)==4) $time_end=(int)$time_end[0];
		else $time_end = (int)($time_end[0].$time_end[1]);
		if(strlen($time_start)==4) $time_start=(int)$time_start[0];
		else $time_start = (int)($time_start[0].$time_start[1]);
		/* 檢查時間 */
		if($time_start >= $time_end)
			return "時間選擇錯誤";
		/************/
		/* 檢查日期是否過了 */
		$nowDate = date("Y-m-d");
		if($date_start && $date_start < $nowDate)
			return "日期已過，無法修改";
		/********************/
		/* repeat */
		$interval = 0;
		$intervalUnit = "";
		$interval = Input::get('date_interval');
		$intervalUnit = Input::get('date_intervalUnit');
		/**********/
		$dateWay = 0;
		$dateTmp = "";
		 //循環方式為日期
			$dateWay=1;
			$year = Input::get('date_year');
			$month = Input::get('date_month');
			$day = Input::get('date_day');
			$dateTmp = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		/**********/
		/* classList */
		$result = DB::table('classList')->get();
		foreach($result as $tmpClass)
			if($tmpClass->name == $class){
				$classId = $tmpClass->id;
				if($tmpClass->name == "4210" &&
					$date_start>="2015-07-00")
					return "此教室已不開放使用";
				break;
			}
		if($classId == -1) return "沒有這間教室";
		/*************/
		/* data */
		$eachDate = self::eachDate($date_start);
		$ar=array();
		$ar['date'] = $date_start;
		$ar['classroom'] = $classId;
		$ar['start_time'] = $time_start;
		$ar['end_time'] = $time_end;
		$ar['user_id'] = 1;
		$ar['username'] = $username;
//		$ar['phone'] = $tel;
//		$ar['email'] = $email;
		$ar['reason'] = $title;
		$ar['type'] = 1;
		$ar['repeat'] = 0; //若有修改，返回預設
		/********/
		/* check */
		$cannotClass = array();
		$canClass = array();
		//Repeat
			 //循環方式為日期
				for( $tmp=$date_start; $tmp<=$dateTmp; $tmp=self::NextDate($intervalUnit, $interval, $tmp) ){
					$result = null;
					if(Session::get('user')=='admin' || $tmp <= $dateLimit['end']['all']) //未超出日期範圍
						$result = DB::table('BorrowList')
									->select('start_time', 'end_time')
									->where('date', $tmp)
									->where('classroom', $classId)
									->get();
					if(self::CheckRepeat($result,$time_start,$time_end)) //if someone borrow the class first
						array_push($cannotClass, $tmp);
					else
						array_push($canClass, $tmp);
				}
			$warning = $class."教室";
			if(!empty($cannotClass)){
				$warning .= "於\\n";
				foreach($cannotClass as $tmp)
					$warning .= " ".$tmp."\\n";
				$warning .= "無法借用或借出\\n";
			}
			else if(!empty($canClass)){
				$warning = "Succeed";
				if($confirm){
					$FirstId = 0;
					foreach($canClass as $tmp){
						$ar['date']=$tmp;
						if(!$FirstId){
							$FirstId = DB::table('BorrowList')->insertGetId($ar);
							$ar['repeat']=$FirstId;
							$result = DB::table('BorrowList')
								->where('id', $FirstId)
								->update($ar);
						}
						else
							$result = DB::table('BorrowList')->insert($ar);
					}
				}
			}
			return $warning;
	}

	private function NextDate($way, $interval, $date){
		$tmp = "";
		if($way == "天")
			$tmp = strtotime(date("Y-m-d", strtotime($date)) . " +".$interval." day");
		else if($way == "週")
			$tmp = strtotime(date("Y-m-d", strtotime($date)) . " +".$interval." week");
		else
			$tmp = strtotime(date("Y-m-d", strtotime($date)) . " +".$interval." month");
		return date("Y-m-d", $tmp);
	}

	protected function CheckRepeat($other, $start, $end){
		$Repeat = false;
		$time=array();
		$time[0]=$start;
		$time[1]=$end;
		foreach($other as $tmp){
			$time1=array();
			$time2=array();
			if($time[0] < $tmp->start_time){
				$time1 = $time;
				$time2[0] = $tmp->start_time;
				$time2[1] = $tmp->end_time;
			}
			else if($time[0] > $tmp->start_time){
				$time2 = $time;
				$time1[0] = $tmp->start_time;
				$time1[1] = $tmp->end_time;
			}
			else{ //start time same
				$Repeat=true;
				break;
			}
			if($time1[1]>$time2[0]){ //conflict
				$Repeat=true;
				break;
			}
		}
		return $Repeat;
	}

}
