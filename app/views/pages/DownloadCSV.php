<?php
	header('Content-type: text/csv');
	header('Content-disposition: attachment;filename=StudentCard.csv');;
	readfile("StudentCard.csv");
?>
