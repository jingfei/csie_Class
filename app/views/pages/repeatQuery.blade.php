@extends('layouts.navbar')
@section('content')
<script>
$(document).ready(function(){
	$(".bordered tr").hover(function(){
		$(this).css('background', 'transparent');
	});
});
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
					<td colspan="8">
						<a href="{{URL::to('modifyForm/0/0/0/0/'.$list[0]->repeat)}}">全部修改</a>
						&nbsp;&nbsp;
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/0/'.$list[0]->repeat)}}');">全部刪除</a>
					</td>
				</tr>
				@endif
				<tr>
					<th>日期</th>
					<th>教室編號</th>
					<th>時間</th>
					<th>課程 / 活動名稱</th>
					<th>借用者</th>
					<th style="width:200px">email</th>
					<th>聯絡電話</th>
					<th>操作</th>
				</tr>
				<tr>
					<td>{{$list[0]->date}}</td>
					<td rowspan="{{count($list)}}">{{$list[0]->classroom}}</td>
					<td rowspan="{{count($list)}}">{{$list[0]->start_time}}:00~{{$list[0]->end_time}}:00</td>
					<td rowspan="{{count($list)}}">{{$list[0]->reason}}</td>
					<td rowspan="{{count($list)}}">{{$list[0]->username}}</td>
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
