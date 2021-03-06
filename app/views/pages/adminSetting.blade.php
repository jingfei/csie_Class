@extends('layouts.navbar')
@section('content')

<style>
td{vertical-align:middle;}
</style>
<script>
$(document).ready(function(){
	$('.jqte').jqte();
	var jqteStatus = true;
});
function modify(event, which, $id){
	event.preventDefault();
	$('#announce'+$id).jqte();
	newHTML="<button onClick='ajaxAnnounce("+$id+");'>確認修改</button>";
	$(which).parent().html(newHTML);
}
function ajaxAnnounce($id){
	content = $("#announce"+$id).parent().parent().find('.jqte_editor').html();
	var request = $.ajax({
		url: '{{URL::to('adminSettingAnnounce')}}',
		type: "POST",
		data: {id: $id, content: content}
	});

	request.success(function( result ){
		alert(result);
		location.reload();
	});

	request.fail(function( jqXHR, textStatus){
		alert("無法更新: "+textStatus);
	});
}
</script>

		<h1 class="content_title" style="font-size:220%;">設定 <small>管理者專用</small></h1>
		<hr/>
		<div style="font-size:1.3em">
			<div style="margin:10px">
				<span style="font-size:1.5em">公告異動</span>
				<br/><br/>
				<table class="bordered">
				<tr>
					<th>日期</th>
					<th>內容</th>
					<th>操作</th>
				</tr>
				@foreach($announce as $item)
				<tr>
					<td>{{$item->date}}</td>
					<td><div id="{{'announce'.$item->id}}">{{$item->content}}</div></td>
					<td style="width:100px">
						<a href="" onClick="modify(event, this,'{{$item->id}}');"><img src="{{asset('img/edit.ico')}}" width="25px" alt="修改"/></a>
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('DeleteAnnounce/'.$item->id)}}');"><img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除"/></a>
					</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="3">
						<div id="announce0"></div>	
						<span><button style="float:right" onClick="modify(event, this,'0');">新增</button></span>
					</td>
				</tr>
				</table>
			</div>
			<hr style="border-top: dashed black 1px;width:96%;margin:20px 2%;"/>
			<div style="margin:10px">
				<span style="font-size:1.5em">借用事由異動</span>
				<br/><br/>
				<table class="bordered">
				<tr>
					<th>id</th>
					<th>事由名稱</th>
					<th>顏色</th>
					<th>操作</th>
				</tr>
				@foreach($reason as $item)
				<tr id="{{'type'.$item->id}}">
					<td>{{$item->id}}</td>
					<td>{{$item->type}}</td>
					<td>
						<div style="display:inline-block;background:{{$item->color}};width:15px; height:15px;"></div>
						{{$item->color}}
					</td>
					<td style="width:100px">
						<a href="{{URL::to('adminSettingType/'.$item->id)}}"><img src="{{asset('img/edit.ico')}}" width="25px" alt="修改"/></a>
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('DeleteType/'.$item->id)}}');"><img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除"/></a>
					</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="4" style="text-align:right"><button onClick="location.href='{{URL::to('adminSettingType')}}';">新增</button></td>
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
					<th>可容納人數</th>
					<th>教室類別</th>
					<th>操作</th>
				</tr>
				@foreach($list as $item)
				<tr id="{{'class'.$item->id}}">
					<td>{{$item->id}}</td>
					<td>{{$item->name}}</td>
					<td>{{$item->capacity}} 人</td>
					<td>{{$item->type}}</td>
					<td style="width:100px">
						<a href="{{URL::to('adminSettingClassroom/'.$item->id)}}"><img src="{{asset('img/edit.ico')}}" width="25px" alt="修改"/></a>
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('DeleteClassroom/'.$item->id)}}');"><img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除"/></a>
					</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="5" style="text-align:right"><button onClick="location.href='{{URL::to('adminSettingClassroom')}}';">新增</button></td>
				</tr>
				</table>
			</div>
		</div>
	
@stop
