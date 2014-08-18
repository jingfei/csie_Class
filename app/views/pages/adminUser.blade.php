@extends('layouts.navbar')
@section('content')

<style>
td{vertical-align:middle;}
</style>

		<h1 class="content_title" style="font-size:220%;">使用者管理 <small>管理者專用</small></h1>
		<hr/>
		<div style="font-size:1.3em">
			<div style="margin:10px">
				<ol>
				<li>
					<span style="font-size:1.5em">學生：</span>
					請至<a href="{{URL::to('query')}}">學生證管理</a>查看
				</li>
				<br/>
				<li><span style="font-size:1.5em">實驗室 / 其他：</span>
				<br/><br/>
				<table class="bordered">
				<tr>
					<th>id</th>
					<th>使用者帳號</th>
					<th>使用者名稱</th>
					<th>操作</th>
				</tr>
				@foreach($list as $key => $item)
				@if($key!=0)
				<tr>
					<td>{{$item->id}}</td>
					<td>{{$item->userid}}</td>
					<td>{{$item->username}}</td>
					<td style="width:150px">
						<a href="{{URL::to('adminSettingUser/'.$item->id)}}"><img src="{{asset('img/edit.ico')}}" width="25px" alt="修改"/></a>
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('DeleteUser/'.$item->id)}}');"><img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除"/></a>
						@if($item->userid!='admin')
						&nbsp;&nbsp;
						<button onClick="if(confirm('確認重設？')) location.replace('{{URL::to('ResetUser/'.$item->id)}}');">重設密碼</button>
						@endif
					</td>
				</tr>
				@endif
				@endforeach
				<tr>
					<td colspan="4" style="text-align:right"><button onClick="location.href='{{URL::to('adminSettingUser')}}';">新增</button></td>
				</tr>
				</table>
				</li>
				</ol>
			</div>
		</div>
	
@stop
