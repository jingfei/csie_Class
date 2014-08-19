<?php

class AdminCardController extends BaseController {

	public function check(){
		if (Session::has('user') || Session::has('new')){
			if(Session::get('user')=='admin')
				return Redirect::to('query');
			else{
				if(Session::has('user')) $id = Session::get('user');
				else $id = Session::get('new');
				$user = DB::table('StudentCard')
							->where('student_id',$id)
							->get();
				return View::make('pages.formCard')->with('id',$id)->with('student',$user?$user[0]:null);
			}
		}
		else
			return Redirect::to('/');
	}

	public function Modify(){
		if(Session::has('user')) $StudentID = Session::get('user');
		else $StudentID = Session::get('new');
		if($StudentID=='admin')
			$StudentID = Session::get('id');
		$user = DB::table('StudentCard')
					->where('student_id',$StudentID)
					->get();
		$name = htmlspecialchars(Input::get('name'));
		$number =  preg_replace('/\D/','',htmlspecialchars(Input::get('number')));
		$department = htmlspecialchars(Input::get('dept'));
		$en_year = htmlspecialchars(Input::get('year'));
		$card = htmlspecialchars(Input::get('card'));
		if($user){
			$user = $user[0];
			DB::table('StudentCard')
				->where('student_id',$StudentID)
				->update(array( 'student_id'=> $StudentID,
								'name' => $name,
								'department' => $department,
								'enrollment_year' => $en_year,
								'student_card' => $card,
								'phone' => $number
							  )
						);
		}
		else{
			DB::table('StudentCard')
				->insert(array( 'student_id' => $StudentID,
								'name' => $name,
								'department' => $department,
								'enrollment_year' => $en_year,
								'student_card' => $card,
								'phone' => $number
							  )
						);
			Session::forget('new');
			Session::put('user', $StudentID);
			Session::put('username', $name);
		}
		if(Session::get('user')=='admin'){
			$dept = Session::get('dept');
			$year = Session::get('year');
			return "<script>alert('finished');</script>".Redirect::to('query?dept='.$dept.'&year='.$year);
		}
		else{
			return "<script>alert('finished');</script>".Redirect::to('/');
		}
	}

	public function All(){

		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('/');
		$Dept = DB::table('StudentCard')->select('department')->distinct()->get();
		$Year = DB::table('StudentCard')->select('enrollment_year')->distinct()->orderBy('enrollment_year')->get();
		return View::make('pages.ListAll')->with('Dept', $Dept)->with('Year',$Year)->with('data',null);
		
	
	}

	public function query(){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('/');
		$Dept = DB::table('StudentCard')->select('department')->distinct()->get();
		$Year = DB::table('StudentCard')->select('enrollment_year')->distinct()->orderBy('enrollment_year')->get();
		$QueryDept = htmlspecialchars( Input::get('dept') );
		$QueryYear = htmlspecialchars( Input::get('year') );
		$data = DB::table('StudentCard');
		if($QueryDept != '不限') $data = $data->where('department', $QueryDept);
		if($QueryYear != '不限') $data = $data->where('enrollment_year', $QueryYear);
		$data = $data->orderBy('department')->orderBy('enrollment_year')->get();
		$output = self::array_to_csv($data);
		$output = "\"學號\", \"姓名\", \"系所\", \"入學年份\", \"學生證號碼\", \"手機號碼\"\n" . $output;
		file_put_contents("StudentCard.csv", $output);
		return View::make('pages.ListAll')->with('Dept', $Dept)->with('Year',$Year)->with('data',$data)->with('CSV',$output?true:false);
	}

	public function addnew(){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('/');
		$StudentID = htmlspecialchars( Input::get('new') );
		$QueryNew = DB::table('StudentCard')->where('student_id', htmlspecialchars( Input::get('new') ))->get();
		if(!$QueryNew && $StudentID){
			Session::put('id',$StudentID);
			return View::make('pages.formCard')->with('id',$StudentID)->with('student',null);
		}
		$data = DB::table('StudentCard')->where('student_id', $StudentID)->get();
		$Dept = DB::table('StudentCard')->select('department')->distinct()->get();
		$Year = DB::table('StudentCard')->select('enrollment_year')->distinct()->orderBy('enrollment_year')->get();
		$output = self::array_to_csv($data);
		$output = "\"學號\", \"姓名\", \"系所\", \"入學年份\", \"學生證號碼\", \"手機號碼\"\n" . $output;
		file_put_contents("StudentCard.csv", $output);
		return View::make('pages.ListAll')->with('Dept', $Dept)->with('Year',$Year)->with('data',$data)->with('CSV',$output?true:false);
	}

	public function queryCard(){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('/');
		$StudentCard = htmlspecialchars( Input::get('new') );
		$data = DB::table('StudentCard')->where('student_card', $StudentCard)->get();
		$Dept = DB::table('StudentCard')->select('department')->distinct()->get();
		$Year = DB::table('StudentCard')->select('enrollment_year')->distinct()->orderBy('enrollment_year')->get();
		$output = self::array_to_csv($data);
		$output = "\"學號\", \"姓名\", \"系所\", \"入學年份\", \"學生證號碼\", \"手機號碼\"\n" . $output;
		file_put_contents("StudentCard.csv", $output);
		return View::make('pages.ListAll')->with('Dept', $Dept)->with('Year',$Year)->with('data',$data)->with('CSV',$output?true:false);
	}

	public function queryName(){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('/');
		$StudentCard = htmlspecialchars( Input::get('new') );
		$data = DB::table('StudentCard')->where('name', $StudentCard)->get();
		$Dept = DB::table('StudentCard')->select('department')->distinct()->get();
		$Year = DB::table('StudentCard')->select('enrollment_year')->distinct()->orderBy('enrollment_year')->get();
		$output = self::array_to_csv($data);
		$output = "\"學號\", \"姓名\", \"系所\", \"入學年份\", \"學生證號碼\", \"手機號碼\"\n" . $output;
		file_put_contents("StudentCard.csv", $output);
		return View::make('pages.ListAll')->with('Dept', $Dept)->with('Year',$Year)->with('data',$data)->with('CSV',$output?true:false);
	}

	public function queryBlock(){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('/');
		$data = DB::table('StudentCard')
					->where('block', 1)
					->get();
		$Dept = DB::table('StudentCard')
					->select('department')
					->distinct()
					->get();
		$Year = DB::table('StudentCard')
					->select('enrollment_year')
					->distinct()
					->orderBy('enrollment_year')
					->get();
		$output = self::array_to_csv($data);
		$output = "\"學號\", \"姓名\", \"系所\", \"入學年份\", \"學生證號碼\", \"手機號碼\"\n" . $output;
		file_put_contents("StudentCard.csv", $output);
		return View::make('pages.ListAll')
					->with('Dept', $Dept)
					->with('Year',$Year)
					->with('data',$data)
					->with('CSV',$output?true:false);
	}

	public function blockState(){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('/');
		$id = htmlspecialchars( Input::get('id') );
		$block = htmlspecialchars( Input::get('block') );
		DB::table('StudentCard')
			->where('id', $id)
			->update(array('block' => $block));
	}

}
