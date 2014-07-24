<?php

class AdminController extends BaseController {

	public function All(){

		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('form');
		$Dept = DB::table('StudentCard')->select('department')->distinct()->get();
		$Year = DB::table('StudentCard')->select('enrollment_year')->distinct()->get();
		return View::make('pages.ListAll')->with('Dept', $Dept)->with('Year',$Year)->with('data',null);
		
	
	}

	public function query(){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('form');
		$Dept = DB::table('StudentCard')->select('department')->distinct()->get();
		$Year = DB::table('StudentCard')->select('enrollment_year')->distinct()->get();
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
			return "<script>something wrong</script>".Redirect::to('form');
		$StudentID = htmlspecialchars( Input::get('new') );
		$QueryNew = DB::table('StudentCard')->where('student_id', htmlspecialchars( Input::get('new') ))->get();
		if(!$QueryNew && $StudentID){
			Session::put('id',$StudentID);
			return View::make('pages.form')->with('id',$StudentID)->with('student',null);
		}
		$data = DB::table('StudentCard')->where('student_id', $StudentID)->get();
		$Dept = DB::table('StudentCard')->select('department')->distinct()->get();
		$Year = DB::table('StudentCard')->select('enrollment_year')->distinct()->get();
		$output = self::array_to_csv($data);
		$output = "\"學號\", \"姓名\", \"系所\", \"入學年份\", \"學生證號碼\", \"手機號碼\"\n" . $output;
		file_put_contents("StudentCard.csv", $output);
		return View::make('pages.ListAll')->with('Dept', $Dept)->with('Year',$Year)->with('data',$data)->with('CSV',$output?true:false);
	}

	public function queryCard(){
		if(Session::get('user')!='admin')
			return "<script>something wrong</script>".Redirect::to('form');
		$StudentCard = htmlspecialchars( Input::get('new') );
		$QueryNew = DB::table('StudentCard')->where('student_card', htmlspecialchars( Input::get('new') ))->get();
		$data = DB::table('StudentCard')->where('student_card', $StudentCard)->get();
		$Dept = DB::table('StudentCard')->select('department')->distinct()->get();
		$Year = DB::table('StudentCard')->select('enrollment_year')->distinct()->get();
		$output = self::array_to_csv($data);
		$output = "\"學號\", \"姓名\", \"系所\", \"入學年份\", \"學生證號碼\", \"手機號碼\"\n" . $output;
		file_put_contents("StudentCard.csv", $output);
		return View::make('pages.ListAll')->with('Dept', $Dept)->with('Year',$Year)->with('data',$data)->with('CSV',$output?true:false);
	}
}
