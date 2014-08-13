@extends('layouts.navbar')
@section('content')

<style>
td{vertical-align:middle;}
</style>
{{HTML::style('css/key.css')}}
<script>
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
	var Url = "/Class2014/adminKey/"+date1+"/"+date2;
	if(Class!==null) Url += "/"+Class;
	if(User) Url += "/"+User;
	location.href = Url;
}

$(document).ready(function(){
	$('input[type=radio]').change(function(){
		var state = $(this).attr('id').split('-')[1];
		var id = $(this).attr('name');
		var request = $.ajax({
			url: '{{URL::to('keyState')}}',
			type: "POST",
			data: {id: id, state: state}
		});

		request.fail(function( jqXHR, textStatus){
			alert("無法更新: "+textStatus);
		});
	});
});

</script>

		<h1 class="content_title" style="font-size:220%;">鑰匙管理 <small>管理者專用</small></h1>
		<hr/>
		<div style="font-size:1.3em">
			<div style="margin:10px">
				<div style="text-align:right;margin:8px 0;border:1px dotted #b0cdcb;padding:5px;line-height:2em;">
					<form>
					查詢: &nbsp;
					<input type="text" size="5" id="y1" value="{{$date1['year']}}" />年
					<input type="text" size="3" id="m1" value="{{$date1['month']}}"/>月
					<input type="text" size="3" id="d1" value="{{$date1['day']}}"/>日
					&nbsp;~&nbsp;
					<input type="text" size="5" id="y2" value="{{$date2['year']}}"/>年
					<input type="text" size="3" id="m2" value="{{$date2['month']}}"/>月
					<input type="text" size="3" id="d2" value="{{$date2['day']}}"/>日
					&nbsp;&nbsp;&nbsp;
					教室編號 <input type="text" size="7" id="adminClass" placeholder="不限" 
					@if($Class) value="{{$Class}}" @endif />
					&nbsp;&nbsp;&nbsp;
					借用者 <input type="text" size="20" id="adminUser" placeholder="不限" 
					@if($User) value="{{$User}}" @endif />
					&nbsp;&nbsp;&nbsp;
					<button type="submit" onClick="adminQuery();return false;">送出</button>
					</form>
				</div>
				<div style="overflow:auto;height:600px">
				<table class="bordered" style="width:1500px;">
				<tr>
					<th>日期</th>
					<th>教室編號</th>
					<th>時間</th>
					<th style="width:150px">課程 / 活動名稱</th>
					<th>借用者</th>
					<th><img src="{{URL::to('img/key.ico')}}" style="width:30px;vertical-align:middle"/></th>
					<th>email</th>
					<th>聯絡電話</th>
				</tr>
				@foreach($list as $key => $item)
				<tr>
					<td>{{$item->date}}</td>
					<td>{{$item->classroom}}</td>
					<td>{{$item->start_time}}:00~{{$item->end_time}}:00</td>
					<td>{{$item->reason}}</td>
					<td>{{$item->username}}</td>
					<td class="key">
						<div>
						<input type="radio" name="{{$item->id}}" id="{{$item->id}}-1" class="radio" @if($item->key==1) checked @endif />
						<label for="{{$item->id}}-1">未借用</label>
						</div>
						<div>
						<input type="radio" name="{{$item->id}}" id="{{$item->id}}-2" class="radio" @if($item->key==2) checked @endif />
						<label for="{{$item->id}}-2">借出</label>
						</div>
						<div>
						<input type="radio" name="{{$item->id}}" id="{{$item->id}}-3" class="radio" @if($item->key==3) checked @endif />
						<label for="{{$item->id}}-3">歸還</label>
						</div>
					</td>
					<td>{{$item->email}}</td>
					<td>{{$item->phone}}</td>
				</tr>
				@endforeach
				</table>
				</div>
			</div>
		</div>
	
@stop
