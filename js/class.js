$(document).ready(function(){
	$(".datepicker").change(function() {
		gotoDate(0);
	});

	$( "#dialog" ).dialog({ 
		autoOpen: false, 
		closeText:"",
		resizable: false, 
		position: {my:"right top", at:"right top", of:"body"}
	});

	var Top=$("#medTable").offset().top;
	$(window).scroll( function(){
		var t=document.documentElement.scrollTop || document.body.scrollTop;
		if($(window).width()<1250) return;
		else if(t+1>=Top)
			$("#medTable").attr("class","TableFix");
		else
			$("#medTable").attr("class","");
	});

	$("nav ul").append('<li><a id="Open" class="myButton">日曆</a></li>');
	$("#Open").on("click",function(e){
		e.preventDefault();
		if($("#dialog").dialog("isOpen"))
			$("#dialog").dialog("close");
		else
			$("#dialog").dialog("open");
	});

	var classID=0;
	var Now = 0;
	var Next = 0;
	$(".no_event").mousedown(function(){
		event.preventDefault();
		Now = parseInt($(this).attr("name").split(";")[0]);
		classID = $(this).attr("name").split(";")[1];
		$(this).css("background","rgba(154,174,255,0.5)");
	});

	$(".no_event").mouseover(function(event){
		if(classID==0) return;
		event.preventDefault();
		var Time = parseInt($(this).attr("name").split(";")[0]);
		if(Next==0)
			$(".no_event[name='"+Time+";"+classID+"']").css("background","rgba(154,174,255,0.5");
		else if(Next>Now){
			for(var i=Now; i<22; ++i){
				if(i>Time)
					$(".no_event[name='"+i+";"+classID+"']").css("background","transparent");
				else
					$(".no_event[name='"+i+";"+classID+"']").css("background","rgba(154,174,255,0.5");
			}
		}
		else if(Next<Now){
			for(var i =Now; i>7; --i){
				if(i<Time) 
					$(".no_event[name='"+i+";"+classID+"']").css("background","transparent");
				else
					$(".no_event[name='"+i+";"+classID+"']").css("background","rgba(154,174,255,0.5");
			}
		}
		Next = Time;
	});

	$("body").mouseup(function(event){
		if(classID==0) return;
		event.preventDefault();
		if(Next===0) Next=Now;
		gotoModify(Now, Next+1, classID);
	});
	
});

