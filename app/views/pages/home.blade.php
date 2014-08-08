@extends('layouts.navbar')
@section('content')

		<h1 class="content_title" style="font-size:220%;">查詢 <small>日期</small></h1>
		<hr/>
		<div style="text-align:center;">
			<div style="color:#2166e5; font-weight:bold; font-size: 1.5em;margin: 10px">
			教室登記 / 查詢開放時間：{{$dateLimit['start']['all']." ~ ".$dateLimit['end']['all']}}
			</div>
			<button class="innerButton" onClick="ClickToday()" >today</button>
			<button class="innerButton" onClick="gotoDate()">登記</button>
		</div>
{{HTML::style('css/date/jquery-ui-1.10.1.css')}}
		<div class="datepicker ll-skin-lugo"></div>
	
@stop
