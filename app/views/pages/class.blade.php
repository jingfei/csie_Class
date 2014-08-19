@extends('layouts.navbar')
@section('content')

<script>
$(document).ready(function(){
	var currentDate = $( ".datepicker" ).datepicker( "setDate", "{{$year."/".$month."/".$day}}" );

	$(".datepicker").change(function() {
		var nowDate = $( ".datepicker" ).datepicker( "getDate" );
		gotoDate();
	});
	$( ".no_event" ).click(function() {
		gotoModify( $(this) );
	});
});

</script>


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
				<table style="width:100%;text-align: center;"><tr>
				<td style="width:25%;">
					<img src="{{asset('img/right.png')}}" onClick="ClassPage(-1)"/>
				</td>
				<td style="width:50%;font-size:2.5em;vertical-align:middle">
					{{$year." / ".$month." / ".$day}}
					@if($warning)
					<span style="font-size:0.8em;">({{$warning}})</span>
					@endif
				</td>
				<td style="width:25%">
					<img src="{{asset('img/left.png')}}"  onClick="ClassPage(1)"/>
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
							<th class="class_time"> </th>
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
							<th class="class_time">{{$time}}</th>
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

