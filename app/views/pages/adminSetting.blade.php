@extends('layouts.navbar')
@section('content')

<style>
td{vertical-align:middle;}
</style>

		<h1 class="content_title" style="font-size:220%;">設定 <small>管理者專用</small></h1>
		<hr/>
		<div style="font-size:1.3em">
			<div style="margin:10px">
				<span style="font-size:1.5em">借用事由異動</span>
				<br/><br/>
				<table class="bordered">
				<tr>
					<th>id</th>
					<th>事由名稱</th>
					<th>操作</th>
				</tr>
				@foreach($reason as $item)
				<tr>
					<td>{{$item->id}}</td>
					<td>{{$item->type}}</td>
					<td style="width:100px">
						<a href="{{URL::to('modifyForm/0/0/0/'.$item->id)}}"><img src="{{asset('img/edit.ico')}}" width="25px" alt="修改"/></a>
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/'.$item->id)}}');"><img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除"/></a>
					</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="3" style="text-align:right"><button>新增</button></td>
				</tr>
				</table>
			</div>
			<hr style="border-top: dashed black 1px;width:96%;margin:20px 2%;"/>
			<div style="margin:10px">
				<span style="font-size:1.5em">教室異動</span>
				<br/><br/>
				<table class="bordered">
				<tr>
					<th>id</th>
					<th>教室名稱</th>
					<th>教室類別</th>
					<th>操作</th>
				</tr>
				@foreach($list as $item)
				<tr>
					<td>{{$item->id}}</td>
					<td>{{$item->name}}</td>
					<td>{{$item->type}}</td>
					<td style="width:100px">
						<a href="{{URL::to('modifyForm/0/0/0/'.$item->id)}}"><img src="{{asset('img/edit.ico')}}" width="25px" alt="修改"/></a>
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/'.$item->id)}}');"><img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除"/></a>
					</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="4" style="text-align:right"><button>新增</button></td>
				</tr>
				</table>
			</div>
		</div>
	
@stop
