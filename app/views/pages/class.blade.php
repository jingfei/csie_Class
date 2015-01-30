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

	$( "#dialog" ).dialog({ 
		autoOpen: false, 
		closeText:"",
		resizable: false, 
		position: {my:"right top", at:"right top", of:"body"}
	});

	var Top=$("#medTable").offset().top;
	$(window).scroll( function(){
		var t=document.documentElement.scrollTop || document.body.scrollTop;
		if($(window).width()<1250) return;
		else if(t+1>=Top)
			$("#medTable").attr("class","TableFix");
		else
			$("#medTable").attr("class","");
	});

	$("nav ul").append('<li><a id="Open" class="myButton">日曆</a></li>');
	$("#Open").on("click",function(e){
		e.preventDefault();
		if($("#dialog").dialog("isOpen"))
			$("#dialog").dialog("close");
		else
			$("#dialog").dialog("open");
	});

});
</script>
<style>
#left_top{
	border: none;
	border-top:80px #D6D3D6 solid;/*上邊框寬度等於表格第一行行高*/
	width:0px;/*讓容器寬度為0*/
	height:0px;/*讓容器高度為0*/
	border-left:117px #BDBABD solid;/*左邊框寬度等於表格第一行第一格寬度*/
	position:relative;/*讓裡面的兩個子容器絕對定位*/
}
#span1{
	border: none;
	font-style:normal;
	display:block;
	position:absolute;
	top:-65px;
	left:-53px;
	width:35px;
}	
#span2{
	border: none;
	font-style:normal;
	display:block;
	position:absolute;
	top:-30px;
	left:-107px;
	width:55px;
}
</style>


		<h1 class="content_title" style="font-size:220%;">查詢 <small>教室</small></h1>
		<hr/>
		<div id="dialog" class="small_date">
			{{HTML::style('css/date/jquery-ui-1.10.1_2.css')}}		
			<div class="datepicker ll-skin-siena"></div>
		</div>
		<div class="class_outer" style="margin:0 12px">
			<div>
				<table style="width:1250px;text-align: center; margin:20px 0;"><tr>
				<td style="width:10%;">
					<img src="{{asset('img/right.png')}}" onClick="gotoDate(-1)"/>
				</td>
				<td style="width:60%;font-size:2.5em;vertical-align:middle">
					{{$year." / ".$month." / ".$day}}
					@if($warning)
					<span style="font-size:0.8em;">({{$warning}})</span>
					@endif
				</td>
				<td style="width:10%">
					<img src="{{asset('img/left.png')}}"  onClick="gotoDate(1)"/>
				</td>
				<td style="width:15%;vertical-align:middle">
					@foreach($type as $tmp)
					<div style="background:{{$tmp->color}};display:inline-block;padding:5px;border-radius:5px;font-size:1.3em" >{{$tmp->type}}</div>
					@endforeach
					<div style="background:#ff9aae;display:inline-block;padding:5px;border-radius:5px;font-size:1.3em;" >可修改</div>
				</td>
				</tr></table>
			</div>
			<!-- table -->
			<div> 
				<div id="class_title">
					<table id="medTable">
						<tr>
						<th class="class_time" style="vertical-align:bottom">
							<div id="left_top">
								<span id="span1">時間</span>
								<span id="span2">教室</span>
							</div>
						</th>
						@for($i=8; $i<=21; ++$i)
							<th class="time_name">
								{{$i}}<br/>
								{{test($i)}}
							</th>
						@endfor
						</tr>
					</table>
				</div>
				<div>
					<table style="margin-bottom:50px">
					@for($i=1; $i<count($data); $i++)
						<tr>
							<th class="new_class">
								{{$data[$i]->name}}<br/>
								{{$data[$i]->type}}<br/>
								@if($data[$i]->capacity) 
									{{"(".$data[$i]->capacity."人)"}}
								@endif
							</th>
						@for($j=8; $j<=21; ++$j)
							@if($table[$j-8][$i][0]==-1 && $disable)
							<td class="class_inner event">
							@elseif($table[$j-8][$i][0]==-1)
							<td class="class_inner {{$disable}} @if(!$disable) no_event @else event @endif" name="{{$j.';'.$data[$i]->name}}">
								<div class="outer_div">
								<div class="inner_div hide_time" @if($disable) style="border:none" @endif>
									<br/>{{$j}}
								</div></div>
							</td>
							@elseif($table[$j-8][$i][0]==1)
							<td class="class_inner event">
								<div class="outer_div">
								@if(!$disable && $table[$j-8][$i][3])
								<div class="inner_div user_div">
									@if($table[$j-8][$i][4])
										<img src="{{asset('img/query.ico')}}" width="25px" alt="連續查詢" onCLick="location.href='{{URL::to('Repeat/'.$table[$j-8][$i][4])}}';" />
									@endif
									<img src="{{asset('img/edit.ico')}}" width="25px" alt="修改" onClick="location.href='{{URL::to('modifyForm/0/0/0/'.$table[$j-8][$i][3])}}';" />
									<img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除" onClick="if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/'.$table[$j-8][$i][3])}}');" />
								@else
								<div style="background:{{$table[$j-8][$i][5]}}" class="inner_div">
								@endif
									<br/>
									{{$table[$j-8][$i][1]}}<br/>
									<small>{{$table[$j-8][$i][2]}}</small>
								</div></div>
							</td>
							@elseif($table[$j-8][$i][0]>1)
							<td class="class_inner event" colspan="{{$table[$j-8][$i][0]}}" style="width:{{70*$table[$j-8][$i][0]-10}}px;">
								<div class="outer_div">
								@if(!$disable && $table[$j-8][$i][3])
								<div class="inner_div user_div">
									@if($table[$j-8][$i][4])
										<img src="{{asset('img/query.ico')}}" width="25px" alt="連續查詢" onCLick="location.href='{{URL::to('Repeat/'.$table[$j-8][$i][4])}}';" />
									@endif
									<img src="{{asset('img/edit.ico')}}" width="25px" alt="修改" onClick="location.href='{{URL::to('modifyForm/0/0/0/'.$table[$j-8][$i][3])}}';" />
									<img src="{{asset('img/delete.ico')}}" width="25px" alt="刪除" onClick="if(confirm('確認刪除?')) location.replace('{{URL::to('Delete/'.$table[$j-8][$i][3])}}');" />
								@else
								<div style="background:{{$table[$j-8][$i][5]}}" class="inner_div">
								@endif
									<br/>
									{{$table[$j-8][$i][1]}}<br/>
									<small>{{$table[$j-8][$i][2]}}</small>
								</div></div>
							</td>
							@endif
						@endfor
						</tr>
					@endfor
					</table>
				</div>
			</div>
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

