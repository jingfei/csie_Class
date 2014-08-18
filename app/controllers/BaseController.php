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
						->first();
			if($tmpUser)
				$tmpUser = $tmpUser->userid;
			else{
				$tmpUser = DB::table('StudentCard')
							->where('id', $user)
							->first();
				if($tmpUser)			
					$tmpUser = $tmpUser->student_id;
			}
			if(!$tmpUser)
				return false;
			if($tmpUser!=Session::get('user')) 
				return false;
		}
		return true;
	}

	public function array_to_csv($fields, $delimiter = ',', $enclosure = '"')
	{
	    $csv = '';
	
	    foreach ($fields as $key => $field) {
			if($key == 'id') continue; //id欄位不用印
	        $first_element = true;
	
	        foreach ($field as $element) {
	            // 除了第一個欄位外, 於 每個欄位 前面都需加上 欄位分隔符號
	            if (!$first_element)
	               $csv .= $delimiter;
	
	            $first_element = false;
	
	            // CSV 遇到 $enclosure, 需要重複一次, ex: " => ""
	            $element = str_replace($enclosure, $enclosure . $enclosure, $element);
	            $csv .= $enclosure . $element . $enclosure;
	        }
	
	        $csv .= "\n";
	    }
	
	    return $csv;
	}
}
