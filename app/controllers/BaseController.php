<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	protected function eachDate($all=null){
		if(!$all) $all=date("Y-m-d");
		$date = array();
		$date['all'] = $all;
		$date['year'] = strtok($all, '-');
		$date['month'] = strtok('-');
		$date['day'] = strtok('-');
		return $date;
	}

	public static function dateLimit(){
		/* 系統開放日期 */
		$start = DB::table('Admin')
						->where('name', 'date_start')
						->first()
						->detail;
		$end = DB::table('Admin')
						->where('name', 'date_end')
						->first()
						->detail;
		$date = array();
		$date['start']=array();
		$date['start']['all'] = $start;
		$date['start']['year'] = strtok($start, '-');
		$date['start']['month'] = strtok('-');
		$date['start']['day'] = strtok('-');
		$date['end']=array();
		$date['end']['all'] = $end;
		$date['end']['year'] = strtok($end, '-');
		$date['end']['month'] = strtok('-');
		$date['end']['day'] = strtok('-');
		/****************/
		return $date;
	}

	protected function CheckUserSession($user){
		if(!Session::has('user'))
			return false;
		if(Session::get('user')!='admin'){
			$tmpUser = DB::table('userList')
						->where('id', $user)
						->first()
						->userid;
			if(!$tmpUser)
				return false;
			if($tmpUser!=Session::get('user'))
				return false;
		}
		return true;
	}

}
