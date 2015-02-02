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

