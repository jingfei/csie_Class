@extends('layouts.navbar')
@section('content')
<script>
$(document).ready(function(){
	$('.openDate').on('change', function(){
		$('#chooseDate').prop('checked', true);
	});
});

function renewDate(){
	var Radio = $('input[name=Open]:checked', '#OpenDate').val();
	var month1 = $('#month1').val();
	var month2 = $('#month2').val();
	var day1 = $('#day1').val();
	var day2 = $('#day2').val();
	var year1 = $('#year1').val();
	var year2 = $('#year2').val();
	var request = $.ajax({
		url: '{{URL::to('adminDate')}}',
		type: "POST",
		data: {
			Open: Radio, 
			month1: month1,
			month2: month2,
			day1: day1,
			day2: day2,
			year1: year1,
			year2: year2
		}
	});
	
	request.done(function(data){
		alert(data);
	});

	request.fail(function( jqXHR, textStatus){
		alert("無法更新: "+textStatus+jqXHR);
	});
}

function adminQuery(){
	/*date1*/
	var y1 = $('#y1').val();
	var m1 = $('#m1').val();
	if(m1.length==1) m1 = '0'+m1; //兩位數
	var d1 = $('#d1').val();
	if(d1.length==1) d1 = '0'+d1; //兩位數
	var date1 = y1+"-"+m1+"-"+d1;
	/*******/
	/*date2*/
	var y2 = $('#y2').val();
	if(!y2) y2=y1;
	var m2 = $('#m2').val();
	if(!m2) m2=m1;
	if(m2.length==1) m2 = '0'+m2; //兩位數
	var d2 = $('#d2').val();
	if(d2.length==1) d2 = '0'+d2; //兩位數
	var date2 = y2+"-"+m2+"-"+d2;
	if(!d2) date2 = date1; 
	/*******/
	var Class = $('#adminClass').val();
	if(!Class) Class=0;
	var User = $('#adminUser').val();
	var Url = "/Class2014/Admin/"+date1+"/"+date2;
	if(Class!==null) Url += "/"+Class;
	if(User) Url += "/"+User;
	location.href = Url;
}
</script>
<style>
.ui-dialog-titlebar-close{
	position: absolute;
	right: 0;
}
#admin-form1 form{
	font-size: 1.3em;
	line-height: 2em;
}
</style>

		<h1 class="content_title" style="font-size:220%;">課程管理 <small>管理者專用</small></h1>
		<hr/>
		<div style="font-size:1.3em">
			<div style="margin:10px">
				<form id="OpenDate" onSubmit="renewDate(); return false;">
					<span style="font-size:1.5em">開放登記日期：</span>
					<input type="radio" id="chooseDate" name="Open" value="yes" @if($date['start']['all']!="2000-01-01") checked @endif />
					<span style="color:red" style="vertical-align:middle">
						<input class="openDate" type="text" size="3" id="year1" value="{{$date['start']['year']}}"/>年
						<input class="openDate" type="text" size="1" id="month1" value="{{$date['start']['month']}}"/>月
						<input class="openDate" type="text" size="1" id="day1" value="{{$date['start']['day']}}"/>日
						 ~ 
						<input class="openDate" type="text" size="3" id="year2" value="{{$date['end']['year']}}"/>年
						<input class="openDate" type="text" size="1" id="month2" value="{{$date['end']['month']}}"/>月
						<input class="openDate" type="text" size="1" id="day2" value="{{$date['end']['day']}}"/>日
						
					</span>
					&nbsp;&nbsp;
					<input type="radio" id="noChoose" name="Open" value="no" @if($date['start']['all']=="2000-01-01") checked @endif />
					<label for="noChoose">不開放</label>
					&nbsp;&nbsp;
					<input type="submit" value="修改"/>
				</form>
			</div>
			<hr style="border-top: dashed black 1px;width:96%;margin:20px 2%;"/>
			<div style="margin:10px">
				<span style="font-size:1.5em">課程異動</span>
					&nbsp;&nbsp;
				<button onClick="location.href='{{URL::to("csvData")}}'">大量教室借用</button>
				<div style="text-align:right;margin:8px 0;border:1px dotted #b0cdcb;padding:5px;line-height:2em;">
					<form>
					查詢: &nbsp;
					<input type="text" size="4" id="y1" value="{{$date1['year']}}" />年
					<input type="text" size="2" id="m1" value="{{$date1['month']}}"/>月
					<input type="text" size="2" id="d1" value="{{$date1['day']}}"/>日
					~
					<input type="text" size="4" id="y2" value="{{$date2['year']}}"/>年
					<input type="text" size="2" id="m2" value="{{$date2['month']}}"/>月
					<input type="text" size="2" id="d2" value="{{$date2['day']}}"/>日
					&nbsp;
					教室編號 <input type="text" size="5" id="adminClass" placeholder="不限" 
					@if($Class) value="{{$Class}}" @endif />
					&nbsp;
					借用者 <input type="text" size="15" id="adminUser" placeholder="不限" 
					@if($User) value="{{$User}}" @endif />
					&nbsp;
					<button type="submit" onClick="adminQuery();return false;">送出</button>
					</form>
				</div>
				<table class="bordered">
				<tr>
					<th>日期</th>
					<th>教室編號</th>
					<th>時間</th>
					<th>課程 / 活動名稱</th>
					<th>借用者</th>
					<th style="width:50px"><img src="{{URL::to('img/key.ico')}}" style="width:30px;vertical-align:middle"/></th>
					<th style="width:180px">email</th>
					<th>聯絡電話</th>
					<th>操作</th>
				</tr>
				@foreach($list as $item)
				<tr>
					<td>{{$item->date}}</td>
					<td>{{$item->classroom}}</td>
					<td>{{$item->start_time}}:00 ~ {{$item->end_time}}:00</td>
					<td>{{$item->reason}}</td>
					<td>{{$item->username}}</td>
					<td>{{$item->key}}</td>
					<td style="width:180px;display:inline-block;overflow:auto;">{{$item->email}}</td>
					<td>{{$item->phone}}</td>
					<td style="width:100px">
						@if($item->repeat)
							<a href="{{URL::to('Repeat/'.$item->repeat)}}"><img src="{{asset('img/query.ico')}}" width="25px" alt="連續查詢"/></a>
						@endif
						<a href="{{URL::to('modifyForm/0/0/0/'.$item->id)}}"><img src="{{asset('img/edit.ico')}}" width="25px" alt="修改"/></a>
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/'.$item->id)}}');"><img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除"/></a>
					</td>
				</tr>
				@endforeach
				</table>
			</div>
		</div>
	
@stop
