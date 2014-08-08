function ClickToday(){
	$( ".datepicker" ).datepicker( "setDate", new Date() );
}

function gotoDate(){
	var selectDate = $( ".datepicker" ).datepicker( "getDate" );
	location.href="class?year="+selectDate.getFullYear()+"&month="+(selectDate.getMonth()+1)+"&day="+selectDate.getDate();
}

function ClassPage(off){
	var name = $(".active").attr('id');
	var num = Number(name.substring(9));
	var Newname = "classpage"+(num+off);
	if(!$("#"+Newname).length) return false;
	$("#"+name).removeClass("active");
	$("#"+Newname).addClass("active");
}

function ShowCycle(object){
	if(object.checked) 
		$('.Repeat').show(); 
	else 
		$('.Repeat').hide();
	$( "#dialog-form" ).dialog({ height: $("#classForm").height()+30 });
}

function initClassform(){
	$( "#dialog-form" ).dialog({ autoOpen: false });
	$( ".no_event" ).click(function() {
		//find time
		startTime = parseInt($(this).attr('name').split(";")[0]);
		endTime = startTime + 1;
		$("#time_start").html(startTime);
		$("#time_start_hidden").val(startTime);
		$("#time_end").children().each(function(){
			/*找出幾點*/
			time = $(this).text();
			if(time.length === 4) time = parseInt(time[0]);
			else time = parseInt(time[0]+time[1]);
			/**********/
			if(time === endTime)
				$(this).attr("selected", true); 
			else if(time < endTime)
				$(this).hide();
			else
				$(this).show();
		});
		//find class
		className = parseInt($(this).attr('name').split(";")[1]);
		$("#form_class").children().each(function(){
			if($(this).text().search(className)!==-1)
				$(this).attr("selected", true); 
		});
		/**/
		$( "#dialog-form" ).dialog( "open" );
	});
	$( "#dialog-form" ).dialog({ closeText: "" });
	$( "#dialog-form" ).dialog({ modal: true });
	$( "#dialog-form" ).dialog({ show: true });
	$( "#dialog-form" ).dialog({ hide: { effect: "explode", duration: 500 } });
	$( "#dialog-form" ).dialog({ width: 340 });
	$( "#dialog-form" ).dialog({ height: 650+30 });
}

