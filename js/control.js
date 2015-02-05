$(document).ready(function(){
	$('#Loginbutton').click(function(){
		$('#loginform').modal({
			fadeDuration: 250,
			fadeDelay: 1.5
		});
		return false;
	});
	$("#LoginLogin").on('submit', userSubmit);
});

function userSubmit(event){
	event.stopPropagation();
	event.preventDefault();
	$pw = $("#inputPasswd").val();
	$studentid = $("#inputStudentid").val();

	var request = $.ajax({
		url: "/Class2014/log_in",
		type: "POST",
		data: 
		{	
			studentid: $studentid,
			pw: $pw
		}
	});

	request.success(function( result ){
		if(result==="yes")
			location.reload();
		else
			alert(result);
	});

	request.fail(function( jqXHR, textStatus){
		alert("無法更新: "+textStatus);
	//	alert("無法更新: "+jqXHR.responseText);
	});
}

function ClickToday(){
	$( ".datepicker" ).datepicker( "setDate", new Date() );
}

function gotoDate($off){
	var selectDate = $( ".datepicker" ).datepicker( "getDate" );
	selectDate.setDate(selectDate.getDate() + $off);
	location.href="/Class2014/class/"+selectDate.getFullYear()+"/"+(selectDate.getMonth()+1)+"/"+selectDate.getDate();
}

function ShowCycle(object){
	if(object.checked) 
		$('.Repeat').show(); 
	else 
		$('.Repeat').hide();
}

function gotoModify(startTime, endTime, className){
	if(startTime>endTime){
		var tmp=endTime;
		endTime=startTime;
		startTime=tmp;
	}
	var obj = {
		startTime: startTime,
		endTime: endTime,
		className: className
	};
	var selectDate = $( ".datepicker" ).datepicker( "getDate" );
	post("/Class2014/modifyForm/"+selectDate.getFullYear()+"/"+(selectDate.getMonth()+1)+"/"+selectDate.getDate(), obj);
}

function post(path, params, method) {
	method = method || "post"; 
	// Set method to post by default if not specified.

	// The rest of this code assumes you are not using a library.
	// It can be made less wordy if you use one.
	var form = document.createElement("form");
	form.setAttribute("method", method);
	form.setAttribute("action", path);

	for(var key in params) {
		if(params.hasOwnProperty(key)) {
			var hiddenField = document.createElement("input");
			hiddenField.setAttribute("type", "hidden");
			hiddenField.setAttribute("name", key);
			hiddenField.setAttribute("value", params[key]);

			form.appendChild(hiddenField);
		}
	}

	document.body.appendChild(form);
	form.submit();
}

function initClassform(){
	$( "#dialog-form" ).dialog({ autoOpen: false });
	$( "#dialog-form" ).dialog({ closeText: "" });
	$( "#dialog-form" ).dialog({ modal: true });
	$( "#dialog-form" ).dialog({ show: true });
	$( "#dialog-form" ).dialog({ hide: { effect: "explode", duration: 500 } });
	$( "#dialog-form" ).dialog({ width: 340 });
	$( "#dialog-form" ).dialog({ height: 650+30 });
}

