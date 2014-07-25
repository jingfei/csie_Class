@extends('layouts.navbar')
@section('content')

		<h1 class="content_title" style="font-size:220%;">查詢 <small>日期</small></h1>
		<hr/>
		<div style="text-align:center;">
			<button class="innerButton" onClick="ClickToday()" >today</button>
			<button class="innerButton" onClick="gotoDate()">登記</button>
		</div>
		<div class="datepicker ll-skin-lugo"></div>
	
@stop
