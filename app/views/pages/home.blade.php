@extends('layouts.navbar')
@section('content')

		<h1 class="content_title" style="font-size:220%;">查詢 <small>日期</small></h1>
		<hr/>
		<div class = "styled-select" style="margin:0 auto; padding:20px; text-align: center; font-size: 16px;">	
		<select name="year">
			@for($i=date('Y')-1; $i<date('Y')+3; ++$i)
				@if($i==date('Y'))
					<option selected="selected">{{$i}}</option>
				@else
					<option>{{$i}}</option>
				@endif
			@endfor
		</select>
		年 &nbsp;&nbsp;
			
		<select name="year">
			@for($i=1; $i<=12; ++$i)
				@if($i==date('n'))
					<option selected="selected">{{$i}}</option>
				@else
					<option>{{$i}}</option>
				@endif
			@endfor
		</select>
		月
		</div>
		<div class="datepicker ll-skin-lugo"></div>
	
@stop
