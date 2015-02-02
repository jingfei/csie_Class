<?php

class KeyLenderController extends ClassController {

	public function checkUser(){
		$StudentCard = htmlspecialchars(Input::get('StudentCard'),ENT_QUOTES);
		$id = DB::table('StudentCard') 
						->select('id') 
						->where('student_card', $StudentCard)
						->first();
		if(!$id) return Response::json(array("status"=>"fail"));
		else return Response::json(array("status"=> (string)$id->id));
	}

	public function queryUser(){
		$UserID = htmlspecialchars(Input::get('UserID'),ENT_QUOTES);
		$date = date("Y-m-d");
		$data = DB::table('BorrowList')
				->select('id', 'classroom', 'start_time', 'end_time')
				->where('date', $date)
				->where('user_id', $UserID)
				->orderBy('classroom')
				->orderBy('start_time')
				->get();
		foreach($data as $tmp){
			$classname = DB::table('classList') 
							->select('name')
							->where('id',$tmp->classroom)
							->first() ->name;
			$tmp->classroom = $classname;
		}
		return Response::json((array)$data);
	}

	public function returnKey(){
		$BorrowID = htmlspecialchars(Input::get('BorrowID'),ENT_QUOTES);
		$data = DB::table('BorrowList')
					->select('key')
					->where('id', $BorrowID)
					->first();
		if(!$data) return Response::json(array("status"=> "fail"));
		$data = $data->key;
		if($data == 2) return Response::json(array("status"=> "someone returned"));
		$data = DB::table('BorrowList')
					->where('id', $BorrowID)
					->update(array("key" => 3));	
		if($data) return Response::json(array("status"=> "success"));
		else return Response::json(array("status"=> "fail"));
	}

	public function findClass(){
		$start = htmlspecialchars(Input::get('StartTime'),ENT_QUOTES);
		$end = htmlspecialchars(Input::get('EndTime'),ENT_QUOTES);
		$date = date("Y-m-d");
		$class = DB::table('classList') 
					->select('id', 'name')
					->get();
		$able = array();
		foreach($class as $tmp){
			$data = DB::table('BorrowList')
						->where('date', $date)
						->where('classroom', $tmp->id)
						->get();
			if(!self::CheckRepeat($data, $start, $end))
				array_push($able, $tmp->name);
		}
		return Response::json($able);
	}

	public function borrow(){
		$UserID = htmlspecialchars(Input::get('UserID'),ENT_QUOTES);
		$ClassName = htmlspecialchars(Input::get('ClassName'),ENT_QUOTES);
		$start = htmlspecialchars(Input::get('StartTime'),ENT_QUOTES);
		$end = htmlspecialchars(Input::get('EndTime'),ENT_QUOTES);
		$date = date("Y-m-d");
		$array = array();
		$classID = DB::table('classList') 
					->select('id')
					->where('name',$ClassName)
					->first();
		if(!$classID){
			$array["status"] = "fail";
			$array["reason"] = "no such class";
			return Response::json($array);
		}
		else $classID = $classID->id;
		$UserName = DB::table('StudentCard') 
					->select('name') 
					->where('id', $UserID)
					->first();
		if(!$UserName){
			$array["status"] = "fail";
			$array["reason"] = "no such user";
			return Response::json($array);
		}
		else $UserName = $UserName->name;
		/* data */
		$ar=array();
		$ar['date'] = $date;
		$ar['classroom'] = $classID;
		$ar['start_time'] = $start;
		$ar['end_time'] = $end;
		$ar['user_id'] = $UserID;
		$ar['username'] = $UserName;
		$ar['phone'] = "";
		$ar['email'] = "";
		$ar['reason'] = "現場借用";
		$ar['type'] = 3;
		$ar['key'] = 2;
		/********/
		$result = DB::table('BorrowList')
					->where('date', $date)
					->where('classroom', $classID)
					->get();
		if(self::CheckRepeat($result,$start,$end)){
			$array["status"] = "fail";
			$array["reason"] = "someone borrowed it first";
			return Response::json($array);
		}
		$result = DB::table('BorrowList')->insert($ar);
		$array["status"] = "success";
		return Response::json($array);
	}

}
