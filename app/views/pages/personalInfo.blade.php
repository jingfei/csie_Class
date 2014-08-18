@extends('layouts.navbar')
@section('content')
<script>
$(document).ready(function(){
	initAdmin();
});

function ShowForm1(){
	$( "#admin-form1" ).dialog( "open" );
}

function changePW(){
	var old = $("#old").val();
	var new1 = $("#new1").val();
	var new2 = $("#new2").val();
	var request = $.ajax({
		url: '{{URL::to('changePW')}}',
		type: "POST",
		data: {old: old, new1: new1, new2: new2}
	});

	request.success(function( result ){
		$("#old").val('');
		$("#new1").val('');
		$("#new2").val('');
		if(result==1)
			alert("更新成功");
		else
			alert(result);
	});

	request.fail(function( jqXHR, textStatus){
		alert("無法更新: "+textStatus);
	});

	return false;
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
#test input[type="text"],
#test input[type="password"]{
	border:1px solid rgba(51,51,51,.5);
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
	padding: 5px;
	font-size: 16px;
	line-height: 20px;
	width: 20%;
	font-family: 'Oleo Script', cursive;
}
</style>

		<h1 class="content_title" style="font-size:220%;">修改 / 查詢 <small>個人資料</small></h1>
		<hr/>
		<!-- form-start -->
		<div id="admin-form1" title="class">
			{{ Form::open(array('class' => 'box', 'url' => 'adminDate', 'method' => 'post')) }}
			<label style="font-size:1.5em"> 開放日期修改 </label>
				<!-- date_start -->
				<div style="margin: 5px 5px 5px 25px">
				</div>
				<!-- date_end -->

			</fieldset>
	
			<footer>
			<input type="submit" value="確認修改" class="btnLogin" tabindex="4">
			</footer>
			
		{{ Form::close() }}
		</div>
		<!-- form-end -->
		<div style="font-size:1.3em">
			<div style="margin:10px">
				@if($Lab)
				<form id='Input' onSubmit="return changePW();">
				<span style="font-size:1.5em;line-height:20px;vertical-align:middle">修改密碼：</span>
				<input type="password" id="old" name="old" placeholder="舊密碼" />
				<input type="password" id="new1" name="new1" placeholder="新密碼" />
				<input type="password" id="new2" name="new2" placeholder="新密碼確認" />
				<input type="submit" />
				</form>
				@elseif($student)
				<span style="font-size:1.5em">個人資料：</span>
				<span style="font-size:1.3em" style="vertical-align:middle">
					{{ $student->student_id }} / 
					{{ $student->name }} / 
					{{ $student->department }} /
					{{ $student->enrollment_year }}年入學 /
					學生證號碼 {{ $student->student_card }} /
					手機 {{ $student->phone }} 
				</span>
				&nbsp;&nbsp;
				<button onClick="ShowForm1();">修改</button>
				@endif
			</div>
			<hr style="border-top: dashed black 1px;width:96%;margin:20px 2%;"/>
			<div style="margin:10px">
				<span style="font-size:1.5em">{{Session::get('username')}} 借用的教室</span>
				<br/><br/>
				<table class="bordered">
				<tr>
					<th>日期</th>
					<th>教室編號</th>
					<th>時間</th>
					<th>課程 / 活動名稱</th>
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
