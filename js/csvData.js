$(function(ready) {
	/* control DatePicker */
	var TrNum=1;
	$( ".DatePicker" ).datepicker();
	$( "#Row-1" ).show();
	$( ".DateStart" ).change(function(){
		var StartDate = $(this).val();
		var tmpNum = $(this).attr('id').substr(12);
		$("#BigDateEnd"+tmpNum).val(StartDate);
		$( "#DateTo"+tmpNum ).show();
	});
	$( ".TimeStart" ).change(function(){
		var StartTime = $(this).val();
		var Index = StartTime.indexOf(":");
		StartTime = parseInt( StartTime.substr(0,Index) )+1;
		var tmpNum = $(this).attr('id').substr(12);
		$("#BigTimeEnd"+tmpNum).val(StartTime+":00");
	});
	/* Add Rows */
	$ ("#Add" ).click(function(){
		++TrNum;
		$( "#Row-"+TrNum ).show();
	});
	/* Ajax Check */
	$(".ClassRow").change(function(){
		var id = $(this).attr('id').split('-')[1];
		var Valid=CheckAllFilled(id);
		if(!Valid){
			$("#Valid"+id).html("<img src='img/notAccept.png' class='icons' /> 資料填寫未完整");
		}
		else{
			var date_start=$("#BigDateStart"+id).val();
			date_start = date_start.replace(/\//g, "-");
			var title=$("#title"+id).val();
			var form_class=$("#BigClass"+id).val();
			var time_start=$("#BigTimeStart"+id).val();
			var time_end=$("#BigTimeEnd"+id).val();
			var date_end=$("#BigDateEnd"+id).val();
			date_end = date_end.replace(/\//g, "-");
			var request = $.ajax({
				url: $("#AjaxUrl").val(),
				type: "POST",
				data: 
				{	
					date_start: date_start, 
					title: title,
					form_class: form_class,
					time_start: time_start,
					time_end: time_end,
					form_repeat: 1,
					date_interval: 1,
					date_intervalUnit: "週",
					date_year: date_end.split("-")[0],
					date_month: date_end.split("-")[1],
					date_day: date_end.split("-")[2],
					date_num: 0
				}
			});

			request.success(function( result ){
				if(result=="Succeed"){
					result="&nbsp;&nbsp;<button onClick='Confirm("+id+");'>確認借用</button>";
					$("#Valid"+id).html(result);
				}
				else{
					result = result.replace(/\\n/g, "<br/>");
					result = "<img src='img/notAccept.png' class='icons' /> " + result;
					$("#Valid"+id).html(result);
				}
			});
	
			request.fail(function( jqXHR, textStatus){
				alert("無法更新: "+textStatus);
//				alert("無法更新: "+jqXHR.responseText);
			});
		}
	});
});

function CheckAllFilled(id){
	var isValid = true;
	$('.Form'+id).each(function() {
		if ( $(this).val() === '' )
			isValid = false;
	});
	return isValid;
}

function Confirm(id){
	var Valid=CheckAllFilled(id);
	if(!Valid){
		$("#Valid"+id).html("something wrong");
	}
	else{
		var date_start=$("#BigDateStart"+id);
		var date_start_val = date_start.val().replace(/\//g, "-");
		var title=$("#title"+id);
		var form_class=$("#BigClass"+id);
		var time_start=$("#BigTimeStart"+id);
		var time_end=$("#BigTimeEnd"+id);
		var date_end=$("#BigDateEnd"+id);
		var date_end_val = date_end.val().replace(/\//g, "-");
		var request = $.ajax({
			url: $("#AjaxUrl").val()+"/1",
			type: "POST",
			data: 
			{	
				date_start: date_start_val, 
				title: title.val(),
				form_class: form_class.val(),
				time_start: time_start.val(),
				time_end: time_end.val(),
				form_repeat: 1,
				date_interval: 1,
				date_intervalUnit: "週",
				date_year: date_end_val.split("-")[0],
				date_month: date_end_val.split("-")[1],
				date_day: date_end_val.split("-")[2],
				date_num: 0
			}
		});

		request.success(function( result ){
			if(result=="Succeed"){
				result="<img src='img/Accept.png' class='icons' /> ";
				result += "&nbsp;&nbsp;已借用";
				$("#Valid"+id).html(result);
				$("#Row-"+id+" td:eq(1)").html(date_start.val()+"~"+date_end.val());
				$("#Row-"+id+" td:eq(2)").html(title.val());
				$("#Row-"+id+" td:eq(3)").html(form_class.val());
				$("#Row-"+id+" td:eq(4)").html(time_start.val()+"~"+time_end.val());
			}
			else{
				result = result.replace(/\\n/g, "<br/>");
				result = "<img src='img/notAccept.png' class='icons' /> " + result;
				$("#Valid"+id).html(result);
			}
		});
	
		request.fail(function( jqXHR, textStatus){
			alert("無法更新: "+textStatus);
		});
	}
}

