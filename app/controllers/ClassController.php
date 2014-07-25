<?php

class ClassController extends \BaseController {

	public static function FindClass(){
		$tmp = DB::table('classList')
				->select('name')
				->orderBy('id')
//				->take(6)
				->get();
		foreach($tmp as &$i)
			$i=$i->name;
		return $tmp;
	}

	public static function FindDate($month, $day, $year){
		$date = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$date = DB::table('BorrowList')
				->select('classroom', 'start_time', 'end_time', 'username', 'reason')
				->where('date', $date)
//				->where('classroom', '<', '7')
				->orderBy('classroom')
				->orderBy('start_time')
				->get();
		return $date;
	}

	public static function SetTable($date, $count){
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

}
