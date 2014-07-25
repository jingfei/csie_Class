@extends('layouts.navbar')
@section('content')

<script>
$(document).ready(function(){
	var currentDate = $( ".datepicker" ).datepicker( "setDate", "{{$year."/".$month."/".$day}}" );

	$(".datepicker").change(function() {
		var nowDate = $( ".datepicker" ).datepicker( "getDate" );
		gotoDate();
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
				</td>
				<td style="width:25%">
					<img src="{{asset('img/left.png')}}"  onClick="ClassPage(1)"/>
				</td>
				</tr></table>
			</div>
			<!-- class page -->
			@for($classpage=1; $classpage<=ceil(count($data)/6.0); $classpage++)
			<div id="classpage{{$classpage}}" 
						class="classpage @if($classpage==1) active @endif">
				<div class="class_table class_title">
					<table>
						<tr>
							<th class="class_time"> </th>
						@for($i=($classpage-1)*6; $i<$classpage*6 && $i<count($data); $i++)
							<th class="class_name">{{$data[$i]}}</th>
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
							@if($table[$time-8][$i][0]==-1)
							<td class="class_inner no_event"></td>
							@elseif($table[$time-8][$i][0]==1)
							<td class="class_inner">
								<div class="inner_div">
									{{$table[$time-8][$i][1]}}<br/>
									<small>{{$table[$time-8][$i][2]}}</small>
								</div>
							</td>
							@elseif($table[$time-8][$i][0]>1)
							<td class="class_inner" rowspan="{{$table[$time-8][$i][0]}}">
								<div class="inner_div">
									{{$table[$time-8][$i][1]}}<br/>
									<small>{{$table[$time-8][$i][2]}}</small>
								</div>
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

