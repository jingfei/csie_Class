<?php

class AdminController extends BaseController {

	public function show($date=null, $date2=null, $Class=0, $User=null){
		if(Session::get('user')!='admin')
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
		foreach($limit as $tmp)
			$tmp->classroom=$className[$tmp->classroom];
		/****************/
		return View::make('pages.admin')
					->with('date', $dateLimit)		//系統開放時間
					->with('list', $limit)			//查詢的資料
					->with('date1', self::eachDate($date))	//查詢起始日期
					->with('date2', self::eachDate($date2))	//查詢終止日期
					->with('Class', $Class)			//查詢教室
					->with('User', $User);			//查詢使用者
	}

	public function updateDate(){
		if(Session::get('user')!='admin')
			return "<script>alert('something wrong');</script>".Redirect::to('/');
		$month1 = Input::get('month1');
		$month2 = Input::get('month2');
		$day1 = Input::get('day1');
		$day2 = Input::get('day2');
		$year1 = Input::get('year1');
		$year2 = Input::get('year2');
		$date1 = date("Y-m-d", mktime(0,0,0,$month1,$day1,$year1));
		$date2 = date("Y-m-d", mktime(0,0,0,$month2,$day2,$year2));
		if($date1 > $date2) return "<script>alert('日期選擇錯誤');</script>".Redirect::to("Admin"); 
		else{
			try{
				$update1 = DB::table('Admin')
							 ->where('name', 'date_start')
							 ->update(array('detail' => $date1));
				$update2 = DB::table('Admin')
				  			 ->where('name', 'date_end')
			  				 ->update(array('detail' => $date2));
			}catch(\Exception $e){
				return "<script>alert('更新失敗');</script>".Redirect::to("Admin");
			
			}
			return "<script>alert('更新成功');</script>".Redirect::to("Admin");
		}
	
	}

	public function adminSetting(){
		/* classList */
		$limit = DB::table('classList')->get();
		$reason = DB::table('typeList')->get();
		return View::make('pages.adminSetting')
					->with('reason', $reason)
					->with('list', $limit);
	}

}
