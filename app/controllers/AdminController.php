<?php

class AdminController extends BaseController {

	public function show($date=null, $date2=null, $factor=null, $detail=null){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('/');
		$dateLimit = self::dateLimit();
		/* 課程異動查詢 */
		if(!$date) $date = date("Y-m-d");
		$limit = DB::table('BorrowList') -> whereBetween('date', array($dateLimit['start']['all'],$dateLimit['end']['all']));
		$list = $limit  ->where('date', $date)
						->get();	
		/****************/
		/* classList */
		$result = DB::table('classList')->get();
		$className = array('no');
		foreach($result as $tmpClass)
			array_push($className, $tmpClass->name);
		/*************/
		return View::make('pages.admin')->with('date', $dateLimit)->with('list', $list)->with('className',$className);
	}

	public function deleteBorrow($_id){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('/');
		DB::table('BorrowList')
			->where('id', $_id)
			->delete();
		return "<script>alert('刪除成功');</script>".Redirect::to('Admin');
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

}
