$(document).ready(function(){
});

function ClickToday(){
	$( ".datepicker" ).datepicker( "setDate", new Date() );
}

function gotoDate(){
	var selectDate = $( ".datepicker" ).datepicker( "getDate" );
	location.href="test?year="+selectDate.getFullYear()+"&month="+(selectDate.getMonth()+1)+"&day="+selectDate.getDate();
}

