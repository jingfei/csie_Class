function ClickToday(){
	$( ".datepicker" ).datepicker( "setDate", new Date() );
}

function gotoDate(){
	var selectDate = $( ".datepicker" ).datepicker( "getDate" );
	location.href="test?year="+selectDate.getFullYear()+"&month="+(selectDate.getMonth()+1)+"&day="+selectDate.getDate();
}

function ClassPage(off){
	var name = $(".active").attr('id');
	var num = Number(name.substring(9));
	var Newname = "classpage"+(num+off);
	if(!$("#"+Newname).length) return false;
	$("#"+name).removeClass("active");
	$("#"+Newname).addClass("active");
}

