@extends('layouts.navbar')
@section('content')

		<h1 class="content_title" style="font-size:220%;">查詢 <small>日期</small></h1>
		<hr/>
		<div class="class_left">sdfsd</div>
		<div class="class_outer">
			<div class="class_table class_title">
				<table>
					<tr>
						<th class="class_time"> </th>
					@foreach($data as $name)
						<th class="class_name">{{$name}}</th>
					@endforeach
					</tr>
				</table>
			</div>
			<div class="class_table class_content">
				<table>
				@for($time=8, $i=0; $time<22; $time++)
					<tr>
						<th class="class_time">{{$time}}</th>
					@for($i=0, $d=0; $i<6; $i++)
						@if($table[$time-8][$i]==-1)
						<td class="class_inner"></td>
						@elseif($table[$time-8][$i]==1)
						<td class="class_inner">
							<div class="inner_div">
								{{$date[$d]->reason}}<br/>
								<small>{{$date[$d++]->username}}</small>
							</div>
						</td>
						@elseif($table[$time-8][$i]>1)
						<td class="class_inner" rowspan="{{$table[$time-8][$i]}}">
							<div class="inner_div">
								{{$date[$d]->reason}}<br/>
								<small>{{$date[$d++]->username}}</small>
							</div>
						</td>
						@endif
					@endfor
					</tr>
				@endfor
				</table>
			</div>
		</div>
@stop
