@extends('layouts.navbar')
@section('content')
<script>
$(document).ready(function(){
	initAdmin();
});

function ShowForm1(){
	$( "#admin-form1" ).dialog( "open" );
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

		<h1 class="content_title" style="font-size:220%;">系統設定 <small>管理者專用</small></h1>
		<hr/>
		<!-- form-start -->
		<div id="admin-form1" title="class">
			{{ Form::open(array('class' => 'box', 'url' => 'adminDate', 'method' => 'post')) }}
			<label style="font-size:1.5em"> 開放日期修改 </label>
				<!-- date_start -->
				<div style="margin: 5px 5px 5px 25px">
				從
				<select name="year1" class="repeat2">
					<option @if(date("Y")==$date['start']['year']) selected="selected" @endif>
						{{date("Y")}}
					</option>
					<option @if(date("Y")+1==$date['start']['year']) selected="selected" @endif>
						{{date("Y")+1}}
					</option>
					<option @if(date("Y")+2==$date['start']['year']) selected="selected" @endif>
						{{date("Y")+2}}
					</option>
					<option @if(date("Y")+3==$date['start']['year']) selected="selected" @endif>
						{{date("Y")+3}}
					</option>
				</select>
				年
				<select name="month1" class="repeat2">
					@for($i=1; $i<=12; ++$i)
						<option @if($date['start']['month']==$i) selected="selected" @endif>
							{{$i}}
						</option>
					@endfor
				</select>
				月
				<select name="day1" class="repeat2">
					@for($i=1; $i<=31; ++$i)
						<option @if($date['start']['day']==$i) selected="selected" @endif>
							{{$i}}
						</option>
					@endfor
				</select>
				日
				<br/>
				<!-- date_end -->
				<!-- date_start -->
				到
				<select name="year2" class="repeat2">
					<option @if(date("Y")==$date['end']['year']) selected="selected" @endif>
						{{date("Y")}}
					</option>
					<option @if(date("Y")+1==$date['end']['year']) selected="selected" @endif>
						{{date("Y")+1}}
					</option>
					<option @if(date("Y")+2==$date['end']['year']) selected="selected" @endif>
						{{date("Y")+2}}
					</option>
					<option @if(date("Y")+3==$date['end']['year']) selected="selected" @endif>
						{{date("Y")+3}}
					</option>
				</select>
				年
				<select name="month2" class="repeat2">
					@for($i=1; $i<=12; ++$i)
						<option @if($date['end']['month']==$i) selected="selected" @endif>
							{{$i}}
						</option>
					@endfor
				</select>
				月
				<select name="day2" class="repeat2">
					@for($i=1; $i<=31; ++$i)
						<option @if($date['end']['day']==$i) selected="selected" @endif>
							{{$i}}
						</option>
					@endfor
				</select>
				日
				<br/>
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
				<span style="font-size:1.5em">系統開放日期：</span>
				<span style="color:red" style="vertical-align:middle">
					{{$date['start']['year']."年".$date['start']['month']."月".$date['start']['day']."日"}} ~ {{$date['end']['year']."年".$date['end']['month']."月".$date['end']['day']."日"}}
				</span>
				&nbsp;&nbsp;
				<button onClick="ShowForm1();">修改</button>
			</div>
			<hr style="border-top: dashed black 1px;width:96%;margin:20px 2%;"/>
			<div style="margin:10px">
				<span style="font-size:1.5em">全部課程異動</span>
				&nbsp;&nbsp;
				<a href="" style="text-decoration:underline">查詢選項</a>
				<br/><br/>
				<table class="bordered">
				<tr>
					<th>日期</th>
					<th>教室</th>
					<th>時間</th>
					<th>課程 / 活動名稱</th>
					<th>借用者</th>
					<th>email</th>
					<th>聯絡電話</th>
					<th>操作</th>
				</tr>
				@foreach($list as $item)
				<tr>
					<td>{{$item->date}}</td>
					<td>{{$className[$item->classroom]}}</td>
					<td>{{$item->start_time}}:00~{{$item->end_time}}:00</td>
					<td>{{$item->reason}}</td>
					<td>{{$item->username}}</td>
					<td>{{$item->email}}</td>
					<td>{{$item->phone}}</td>
					<td>
						@if($item->repeat)
							<a href="{{URL::action('AdminController@show')}}">連續查詢</a> / 
						@endif
						<a href="{{URL::to('modifyForm/0/0/0/'.$item->id)}}">修改</a> / 
						<a href="javascript: if(confirm('確認刪除?')) location.replace('{{URL::to('AdminDelete/'.$item->id)}}');">刪除</a>
					</td>
				</tr>
				@endforeach
				</table>
			</div>
		</div>
	
@stop
