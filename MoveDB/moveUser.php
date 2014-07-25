<?php
	const DB_HOST = 'localhost';
	const DB_USER = 'root';
	const DB_PASS = '955047';
	const DB_NAME = 'class_system';
	$mysqli=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	$mysqli->query("SET NAMES 'latin1'");
	$sql="SELECT * FROM `users` WHERE 1";
	$result = $mysqli->query($sql);
	$mysqli->query("SET NAMES 'utf8'");
	$i=0;
	while($rows=$result->fetch_array()){
//	$rows=$result->fetch_array();
		$i++;
		if(strpos($rows['userid'], "f7") !== false) continue;
		$userid = $rows['userid'];
		$username = $rows['username'];
		$password = md5($rows['password']);
		$sql="INSERT INTO `userList`(`userid`, `username`, `password`) VALUES ('$userid','$username','$password')";
		$mysqli->query($sql);
	}
	echo $i;

