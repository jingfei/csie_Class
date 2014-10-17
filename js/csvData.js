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
			$("#Valid"+id).html("wrong");
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
					form_user: "最高管理者",
					old: 0,
					date_start: date_start, 
					title: title,
					form_class: form_class,
					time_start: time_start,
					old_repeat: 0,
					time_end: time_end,
					form_repeat: 1,
					date_interval: 1,
					date_intervalUnit: "週",
					Repeat_end: "date",
					date_year: date_end.split("-")[0],
					date_month: date_end.split("-")[1],
					date_day: date_end.split("-")[2],
					date_num: 0
				}
			});

			request.success(function( result ){
				result = result.replace(/\\n/g, "<br/>");
				$("#Valid"+id).html(result);
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

