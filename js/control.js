function ClickToday(){
	$( ".datepicker" ).datepicker( "setDate", new Date() );
}

function gotoDate(){
	var selectDate = $( ".datepicker" ).datepicker( "getDate" );
	location.href="/Class2014/class/"+selectDate.getFullYear()+"/"+(selectDate.getMonth()+1)+"/"+selectDate.getDate();
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
}

function gotoModify(thisObj){
	//find time
	var startTime = parseInt(thisObj.attr('name').split(";")[0]);
	var endTime = startTime + 1;
	//find class
	var className = parseInt(thisObj.attr('name').split(";")[1]);
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

function initAdmin(){
	$( "#admin-form1" ).dialog({ closeText: "x" });
	$( "#admin-form1" ).dialog({ modal: true });
	$( "#admin-form1" ).dialog({ show: true });
	$( "#admin-form1" ).dialog({ hide: { effect: "explode", duration: 500 } });
	$( "#admin-form1" ).dialog({ width: 340 });
	$( "#admin-form1" ).dialog({ title: "" });

}

