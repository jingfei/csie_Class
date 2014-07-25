<?php
	const DB_HOST = 'localhost';
	const DB_USER = 'root';
	const DB_PASS = '955047';
	const DB_NAME = 'class_system';
	$mysqli=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	$mysqli->query("SET NAMES 'utf8'");
	/* classList */
	$sql = "Select `name` From `classList` Where 1 order by `id`";
	$result = $mysqli->query($sql);
	$Class = array("no");
	while($rows=$result->fetch_array())
		array_push($Class, $rows['name']);
	/*************/
	/* userList */
	$sql = "Select `username` From `userList` Where 1 order by `id`";
	$result = $mysqli->query($sql);
	$User = array();
	while($rows=$result->fetch_array())
		array_push($User, $rows['username']);
	/*************/
	/* typeList */
	$sql = "Select `type` From `typeList` Where 1 order by `id`";
	$result = $mysqli->query($sql);
	$Type = array("no");
	while($rows=$result->fetch_array())
		array_push($Type, $rows['type']);
	/*************/
	$mysqli->query("SET NAMES 'latin1'");
	$sql="SELECT * FROM `choiceList` WHERE 1 order by `id`";
	$result = $mysqli->query($sql);
	$mysqli->query("SET NAMES 'utf8'");
	$i=0;
	while($rows=$result->fetch_array()){
//	$rows=$result->fetch_array();
		$month=$rows['month'];
		$day=$rows['day'];
		$year=$rows['year'];
		$classroom = $rows['classroom'];
		$start_time = $rows['starttime'];
		$end_time = $rows['endtime'];
		$username = $rows['userid'];
		$phone = $rows['tel'];
		$email = $rows['mail'];
		$reason = $rows['classname']; 
		$type = $rows['reason'];
		$date = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		/* link data */
		$classroom = array_search($classroom, $Class);
		if(!$classroom) continue;
		$type = array_search($type, $Type);
		if(!$type) continue;
		$user_id = array_search($username, $User);
		if(!$user_id) $user_id=0;
		if(strtotime($date)<strtotime(date("Y-m-d", mktime(0,0,0,1,1,2014))))
			continue;
		/*************/
		$i++;
		$sql="INSERT INTO `BorrowList`(`date`, `classroom`, `start_time`, `end_time`, `user_id`, `username`, `phone`, `email`, `reason`, `type`) VALUES ('$date',$classroom,$start_time,$end_time,$user_id,'$username', '$phone','$email','$reason',$type)";
		$mysqli->query($sql);

	}
	echo $i."\n";
	

function convertBig5ToUTF8($v){
	return mb_convert_encoding($v, "UTF-8", "big5");
}
?>
