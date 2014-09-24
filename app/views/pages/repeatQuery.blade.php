@extends('layouts.navbar')
@section('content')
<script>
$(document).ready(function(){
	$(".bordered tr").hover(function(){
		$(this).css('background', 'transparent');
	});
});

function addRepeatDate(){
	var yearNew = $("#yearNew").val();
	var monthNew = $("#monthNew").val();
	var dayNew = $("#dayNew").val();
	if(!yearNew || !monthNew || !dayNew){
		alert("日期填寫未完整");
		return;
	}
	var request = $.ajax({
		url: '{{URL::to('addRepeatDate')}}',
		type: "POST",
		data: {
			_id: {{$_id}}, 
			year: yearNew,
			month: monthNew,
			day: dayNew
		}
	});

	request.success(function( result ){
		alert(result);
		location.reload();
	});

	request.fail(function( jqXHR, textStatus){
		alert("無法更新: "+textStatus);
	});

}

function repeatSplit(){
	var yearSplit = $("#yearSplit").val();
	var monthSplit = $("#monthSplit").val();
	var daySplit = $("#daySplit").val();
	if(!yearSplit || !monthSplit || !daySplit){
		alert("日期填寫未完整");
		return;
	}

	var warning = "確定將日期"+yearSplit+"-"+monthSplit+"-"+daySplit+
					"~{{end($list)->date}}分割?\n(分割後無法合併)";
	if(!confirm(warning))
		return;

	var request = $.ajax({
		url: '{{URL::to('repeatSplit')}}',
		type: "POST",
		data: {
			_id: {{$_id}}, 
			year: yearSplit,
			month: monthSplit,
			day: daySplit
		}
	});

	request.success(function( result ){
		if( {{$_id}} == result)
			alert("日期分割失敗");
		else
			alert("日期分割成功");
		location.href=result;
	});

	request.fail(function( jqXHR, textStatus){
		alert("無法更新: "+textStatus);
	});

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
.bordered td{
	vertical-align: middle;
}
</style>

		<h1 class="content_title" style="font-size:220%;">查詢/修改 <small>連續借用</small></h1>
		<hr/>
		<div style="font-size:1.3em">
			<div style="margin:10px">
				<table class="bordered">
				@if($change)
				<tr>
					<td colspan="3" style="border-right:none">
						<form action="{{URL::to('modifyForm/0/0/0/0/'.$list[0]->repeat)}}" style="display:inline"> 
							<input type="submit" value="全部修改" />
						</form>
						&nbsp;&nbsp;
						<form action="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/0/'.$list[0]->repeat)}}');" style="display:inline;"> 
							<input type="submit" value="全部刪除" />
						</form>
					</td>
					<td colspan="3" style="border-left:none;">
					<form onsubmit="addRepeatDate(); return false;">
					新增日期: 
						<input class="openDate" type="text" size="3" id="yearNew" />年
						<input class="openDate" type="text" size="1" id="monthNew" />月
						<input class="openDate" type="text" size="1" id="dayNew" />日
						<input type="submit" value="新增" />
					</form>
					</td>
					<td colspan="3" style="border-left:none;">
					<form onsubmit="repeatSplit(); return false;">
					分割日期: 
						<input class="openDate" type="text" size="3" id="yearSplit" />年
						<input class="openDate" type="text" size="1" id="monthSplit" />月
						<input class="openDate" type="text" size="1" id="daySplit" />日
						<input type="submit" value="分割" />
					</form>
					</td>
				</tr>
				@endif
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
				<tr>
					<td>{{$list[0]->date}}</td>
					<td rowspan="{{count($list)}}">{{$list[0]->classroom}}</td>
					<td rowspan="{{count($list)}}">{{$list[0]->start_time}}:00~{{$list[0]->end_time}}:00</td>
					<td rowspan="{{count($list)}}">{{$list[0]->reason}}</td>
					<td rowspan="{{count($list)}}">{{$list[0]->username}}</td>
					<td>{{$list[0]->key}}</td>
					<td rowspan="{{count($list)}}">{{$list[0]->email}}</td>
					<td rowspan="{{count($list)}}">{{$list[0]->phone}}</td>
					<td>
						<a href="{{URL::to('modifyForm/0/0/0/'.$list[0]->id)}}"><img src="{{asset('img/edit.ico')}}" width="25px" alt="修改"/></a>
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/'.$list[0]->id)}}');"><img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除"/></a>
					</td>
				</tr>
				@foreach($list as $key => $item)
				@if($key!=0)
				<tr>
					<td>{{$item->date}}</td>
					<td>{{$item->key}}</td>
					<td>
						<a href="{{URL::to('modifyForm/0/0/0/'.$item->id)}}"><img src="{{asset('img/edit.ico')}}" width="25px" alt="修改"/></a>
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/'.$item->id)}}');"><img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除"/></a>
					</td>
				</tr>
				@endif
				@endforeach
				</table>
			</div>
		</div>
	
@stop
