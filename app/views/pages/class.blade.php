@extends('layouts.navbar')
@section('content')

<script>
$(document).ready(function(){
	var currentDate = $( ".datepicker" ).datepicker( "setDate", "{{$year."/".$month."/".$day}}" );

	$(".datepicker").change(function() {
		gotoDate(0);
	});
	$( ".no_event" ).click(function() {
		gotoModify( $(this) );
	});
});
</script>
<style>
#left_top{
	border: none;
	border-top:70px #D6D3D6 solid;/*上邊框寬度等於表格第一行行高*/
	width:0px;/*讓容器寬度為0*/
	height:0px;/*讓容器高度為0*/
	border-left:45px #BDBABD solid;/*左邊框寬度等於表格第一行第一格寬度*/
	position:relative;/*讓裡面的兩個子容器絕對定位*/
}
#span1{
	border: none;
	font-style:normal;
	display:block;
	position:absolute;
	top:-60px;
	left:-33px;
	width:35px;
}	
#span2{
	border: none;
	font-style:normal;
	display:block;
	position:absolute;
	top:-25px;
	left:-57px;
	width:55px;
}
</style>


		<h1 class="content_title" style="font-size:220%;">查詢 <small>教室</small></h1>
		<hr/>
		<div class="small_date">
			{{HTML::style('css/date/jquery-ui-1.10.1_2.css')}}		
			<div class="datepicker ll-skin-siena"></div>
		</div>
		<div class="class_left">
		</div>
		<div class="class_outer">
			<div>
				<table style="width:680px;text-align: center;"><tr>
				<td style="width:20%;">
					<img src="{{asset('img/right.png')}}" onClick="gotoDate(-1)"/>
				</td>
				<td style="width:60%;font-size:2.5em;vertical-align:middle">
					{{$year." / ".$month." / ".$day}}
					@if($warning)
					<span style="font-size:0.8em;">({{$warning}})</span>
					@endif
				</td>
				<td style="width:20%">
					<img src="{{asset('img/left.png')}}"  onClick="gotoDate(1)"/>
				</td>
				</tr></table>
			</div>
			<!-- class page -->
			<div style="margin:5px;text-align:right;">
				@foreach($type as $tmp)
				<div style="background:{{$tmp->color}};display:inline-block;padding:3px" >{{$tmp->type}}</div>
				@endforeach
				<div style="background:#ff9aae;display:inline-block;padding:3px" >可修改</div>
			</div>
			<!-- table -->
			@for($classpage=1; $classpage<=ceil(count($data)/6.0); $classpage++)
			<div id="classpage{{$classpage}}" 
						class="classpage @if($classpage==1) active @endif">
				<div class="class_table class_title">
					<table>
						<tr>
							<td style="border:none"></td>
							<td colspan="6" style="text-align:center;border-left:none;line-height:30px;">
								<img src="{{asset('img/left-arrow.gif')}}" height="30px" alt="上一頁" style="border:none;float:left" onClick="ClassPage(-1);"/>
								<span style="border:none;font-size:1.5em" id="PageNum">Page. {{$classpage}}</span>
								<img src="{{asset('img/right-arrow.gif')}}" height="30px" alt="下一頁" style="border:none;float:right" onClick="ClassPage(1);"/>
							</td>
						</tr>
						<tr>
						<th class="class_time" style="vertical-align:bottom">
							<div id="left_top">
								<span id="span1">教室</span>
								<span id="span2">時間</span>
							</div>
						</th>
						@for($i=($classpage-1)*6; $i<$classpage*6 && $i<count($data); $i++)
							<th class="class_name">
								{{$data[$i]->name.'<br/>'.$data[$i]->type}}
							</th>
						@endfor
						</tr>
					</table>
				</div>
				<div class="class_table class_content">
					<table>
					@for($time=8; $time<22; $time++)
						<tr>
							<th class="class_time">
								{{$time}}:10<br/>
								<span style="border:none;line-height:2.5em">{{test($time)}}</span>
							</th>
						@for($i=($classpage-1)*6+1; $i<=$classpage*6 &&$i<=count($data); $i++)
							@if($table[$time-8][$i][0]==-1 && $disable)
							<td class="class_inner">
							@elseif($table[$time-8][$i][0]==-1)
							<td class="class_inner {{$disable}} @if(!$disable) no_event @endif" name="{{$time.';'.$data[$i-1]->name}}">
								<div class="outer_div">
								<div class="inner_div" @if($disable) style="border:none" @endif>
								</div></div>
							</td>
							@elseif($table[$time-8][$i][0]==1)
							<td class="class_inner">
								<div class="outer_div">
								@if(!$disable && $table[$time-8][$i][3])
								<div class="inner_div user_div">
									<br/>
									@if($table[$time-8][$i][4])
										<img src="{{asset('img/query.ico')}}" width="25px" alt="連續查詢" onCLick="location.href='{{URL::to('Repeat/'.$table[$time-8][$i][4])}}';" />
									@endif
									<img src="{{asset('img/edit.ico')}}" width="25px" alt="修改" onClick="location.href='{{URL::to('modifyForm/0/0/0/'.$table[$time-8][$i][3])}}';" />
									<img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除" onClick="if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/'.$table[$time-8][$i][3])}}');" />
								@else
								<div style="background:{{$table[$time-8][$i][5]}}" class="inner_div">
								@endif
									<br/>
									{{$table[$time-8][$i][1]}}<br/>
									<small>{{$table[$time-8][$i][2]}}</small>
								</div></div>
							</td>
							@elseif($table[$time-8][$i][0]>1)
							<td class="class_inner" rowspan="{{$table[$time-8][$i][0]}}" style="height:{{70*$table[$time-8][$i][0]}}px;">
								<div class="outer_div">
								@if(!$disable && $table[$time-8][$i][3])
								<div class="inner_div user_div">
									<br/>
									@if($table[$time-8][$i][4])
										<img src="{{asset('img/query.ico')}}" width="25px" alt="連續查詢" onCLick="location.href='{{URL::to('Repeat/'.$table[$time-8][$i][4])}}';" />
									@endif
									<img src="{{asset('img/edit.ico')}}" width="25px" alt="修改" onClick="location.href='{{URL::to('modifyForm/0/0/0/'.$table[$time-8][$i][3])}}';" />
									<img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除" onClick="if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/'.$table[$time-8][$i][3])}}');" />
								@else
								<div style="background:{{$table[$time-8][$i][5]}}" class="inner_div">
								@endif
									<br/>
									{{$table[$time-8][$i][1]}}<br/>
									<small>{{$table[$time-8][$i][2]}}</small>
								</div></div>
							</td>
							@endif
						@endfor
						</tr>
					@endfor
					</table>
				</div>
			</div>
			@endfor
		</div>
@stop

<?php
function test($time){
	switch($time){
		case 8: return "第一節";
		case 9: return "第二節";
		case 10: return "第三節";
		case 11: return "第四節";
		case 12: return "N";
		case 13: return "第五節";
		case 14: return "第六節";
		case 15: return "第七節";
		case 16: return "第八節";
		case 17: return "第九節";
		case 18: return "A";
		case 19: return "B";
		case 20: return "C";
		case 21: return "D";
		case 22: return "E";
		default: return "";
	}
}
?>

