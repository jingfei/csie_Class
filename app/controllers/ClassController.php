<?php

class ClassController extends \BaseController {

	public function FindClass(){
		$tmp = DB::table('classList')
				->select('name', 'type')
				->orderBy('id')
				->get();
		return $tmp;
	}

	public function FindDate($month, $day, $year){
		$date = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$date = DB::table('BorrowList')
				->select('classroom', 'start_time', 'end_time', 'username', 'reason')
				->where('date', $date)
				->orderBy('classroom')
				->orderBy('start_time')
				->get();
		return $date;
	}

	public function SetTable($date, $count){
		/*initialize array*/
		$tmp = array();
		for($i=0; $i<=$count; $i++) array_push($tmp, array(-1,"","") );
		$table = array();
		for($i=8; $i<22; $i++) array_push($table, $tmp);
		/******************/
		foreach($date as $obj){
			$during = $obj->end_time - $obj->start_time;
			$table[$obj->start_time-8][$obj->classroom][0] = $during;
			$table[$obj->start_time-8][$obj->classroom][1] = $obj->reason;
			$table[$obj->start_time-8][$obj->classroom][2] = $obj->username;
			for($i=$obj->start_time-8+1, $j=1; $j<$during; $j++, $i++)
				$table[$i][$obj->classroom][0] = 0;
		}
		return $table;
	}

	public function getClass($year=null, $month=null, $day=null){
		if(!$year) $year=date("Y"); 
		if(!$month) $month=date("m");
		if(!$day) $day=date("d"); 
		$thisDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$dateLimit = self::dateLimit();
		if($thisDate>$dateLimit['end']['all'] || $thisDate<$dateLimit['start']['all'])
			return "<script>alert('日期錯誤');</script>".Redirect::to('/');
		$data = self::FindClass();
		$date = self::FindDate($month, $day, $year);
		$table = self::SetTable($date, count($data));
		return View::make('pages.class')->with('data',$data)->with('table',$table)->with('date', $date)->with('month', $month)->with('year', $year)->with('day', $day);
	}

	public function classForm($year, $month, $day, $old=null){
		if(!Session::has('user'))
			return "<script>alert('請登入');</script>".Redirect::to('Login');
		$diffUser=null;
		$User = DB::table('userList')
					->where('userid', Session::get('user'))
					->first()
					->username;
		$result = null;
		if($old){
			$result = DB::table('BorrowList')->where('id', $old)->first();
			if(!$result) 
				return "<script>alert('something wrong...');</script>".Redirect::to('/');
			if(!self::CheckUserSession($result->user_id))
				return "<script>alert('something wrong...');</script>".Redirect::to('/');
			$diffUser=$result->username;
			$year = strtok($result->date, '-');
			$month = strtok('-');
			$day = strtok('-');
			$className = DB::table('classList')
							->where('id', $result->classroom)
							->first()
							->name;
			$startTime = $result->start_time;
			$endTime = $result->end_time;
		}
		else{
			$className = htmlspecialchars( Input::get('className') );
			$startTime = htmlspecialchars( Input::get('startTime') );
			$endTime = htmlspecialchars( Input::get('endTime') );
		}
		$thisDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$dateLimit = self::dateLimit();
		if($thisDate>$dateLimit['end']['all'] || $thisDate<$dateLimit['start']['all'])
			return "<script>alert('日期錯誤');</script>".Redirect::to('/');
		$data = self::FindClass();
		return View::make('pages.form')
					->with('data',$data)
					->with('month', $month)
					->with('year', $year)
					->with('day', $day)
					->with('user', $diffUser ? $diffUser : $User)
					->with('old', $result)
					->with('className', $className)
					->with('startTime', $startTime)
					->with('endTime', $endTime);
	}

	public function Borrow(){
		if(!Session::has('user'))
			return "<script>alert('請登入');</script>".Redirect::to('Login');
		$user = htmlspecialchars( Input::get('form_user') );
		$old = Input::get('old'); 
		if($old && Session::get('user')!='admin'){ //檢查post ID是否為本人
			$result = DB::table('BorrowList')
						->where('id', $old)
						->first();
			if(!self::CheckUserSession($result->user_id))
				return "<script>alert('something wrong...');</script>".Redirect::to('/');
		}
		$userInfo = DB::table('userList')
						->where('username', $user)
						->first();
		if(Session::get('user')!='admin' && $userInfo->userid!=Session::get('user')) //登入與表單使用者不同
				return "<script>alert('something wrong...');</script>".Redirect::to('/');
		$date_start = htmlspecialchars( Input::get('date_start') );
		$title = htmlspecialchars( Input::get('title') );
		$class = htmlspecialchars( Input::get('form_class') );
		$class = strtok($class, " ");
		$classId = -1;
		$time_start = htmlspecialchars( Input::get('time_start') );
		$time_end = htmlspecialchars( Input::get('time_end') );
		if(strlen($time_end)==4) $time_end=(int)$time_end[0];
		else $time_end = (int)($time_end[0].$time_end[1]);
		$email = htmlspecialchars( Input::get('form_email') );
		$tel = htmlspecialchars( Input::get('form_tel') );
		$type = htmlspecialchars( Input::get('form_reason') );
		$typeId = -1;
		/*檢查日期*/
		$dateLimit = self::dateLimit();
		if($date_start>$dateLimit['end']['all'] || $date_start<$dateLimit['start']['all'])
			return "<script>alert('日期錯誤');</script>".Redirect::to('/');
		/**********/
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
		if($typeId == -1) return "<script>alert('請選擇借用事由');</script>".Redirect::to('class');
		/*************/
		/* check */
		else if(!$Repeat){
			$result = DB::table('BorrowList')
						->where('date', $date_start)
						->where('classroom', $classId)
						->whereBetween('start_time', array($time_start,$time_end-1));
			if($old) $result->whereNotIn('id', array($old));
			$result = $result->get();
			$warning = "";
			/* if someone has borrowed the class first */
			if(count($result)){ 
				$warning = $class."教室已於".$date_start." ".$result[0]->start_time.":00借出，\\n請確認後重新借用";
				return "<script>alert('$warning');</script>".Redirect::to('class');
			}
			/*******************************************/
			/* 更新資料 */
			else if($old){  
				$ar=array();
				$ar['date'] = $date_start;
				$ar['classroom'] = $classId;
				$ar['start_time'] = $time_start;
				$ar['end_time'] = $time_end;
				$ar['user_id'] = $userInfo->id;
				$ar['username'] = $userInfo->username;
				$ar['phone'] = $tel;
				$ar['email'] = $email;
				$ar['reason'] = $title;
				$ar['type'] = $typeId;
				$result = DB::table('BorrowList')
							->where('id', $old)
							->update($ar);
				$year = strtok($date_start, '-');
				$month = strtok('-');
				$day = strtok('-');
				return "<script>alert('更新成功');</script>".Redirect::to('class/'.$year.'/'.$month.'/'.$day);
			}
			/************/
			else{
				$warning = $class."教室於".$date_start."   ".$time_start.":00 ~ ".$time_end.":00 成功借用!!";
				return "<script>alert('$warning');</script>".Redirect::to('class');
			}
		}
		else{
			$cannotClass = array();
			$canClass = array();
			if($dateWay == 1){ //循環方式為日期
				for( $tmp=$date_start; $tmp<=$dateTmp; $tmp=self::NextDate($intervalUnit, $interval, $tmp) ){
					$result = null;
					if($tmp <= $dateLimit['end']['all']) //未超出日期範圍
						$result = DB::table('BorrowList')
									->where('date', $tmp)
									->where('classroom', $classId)
									->whereBetween('start_time', array($time_start,$time_end-1))
									->get();
					if(count($result)) //if someone borrow the class first
						array_push($cannotClass, $tmp);
					else
						array_push($canClass, $tmp);
				}
			}
			else if($dateWay == 2){ //循環方式為次數
				$tmp=$date_start;
				for($i=0; $i<$dateTmp; ++$i){
					$result = null;
					if($tmp <= $dateLimit['end']['all']) //未超出日期範圍
						$result = DB::table('BorrowList')
									->where('date', $tmp)
									->where('classroom', $classId)
									->whereBetween('start_time', array($time_start,$time_end-1))
									->get();
					if(count($result)) //if someone borrow the class first
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
				$warning .= "於\\n";
				foreach($canClass as $tmp)
					$warning .= " ".$tmp."\\n";
				$warning .= "成功借用!!";
			}
			else
				$warning .= "借用未成功";
			return "<script>alert('$warning');</script>".Redirect::to('class');
		}
	}

	function NextDate($way, $interval, $date){
		$tmp = "";
		if($way == "天")
			$tmp = strtotime(date("Y-m-d", strtotime($date)) . " +".$interval." day");
		else if($way == "週")
			$tmp = strtotime(date("Y-m-d", strtotime($date)) . " +".$interval." week");
		else
			$tmp = strtotime(date("Y-m-d", strtotime($date)) . " +".$interval." month");
		return date("Y-m-d", $tmp);
	}

}
