@extends('layouts.navbar')
@section('content')

<style>
.round-div{
	font-family: Microsoft JhengHei;
	border:1px solid gray;
	color: #0e2d66;
	padding:10px;
	background: rgba(204,221,255,0.6);
	box-shadow: 5px 5px 5px #888888;
	margin: 10px auto;
	width: 80%;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px; /* future proofing */
	-khtml-border-radius: 10px; /* for old Konqueror browsers */
}
.round-div p{
	margin: 5px;
}
.round-div:hover{ background: rgba(204,221,255,0.8); }
.datepicker>div{
	box-shadow: 10px 10px 5px #888888;
}
</style>
<script>
$(document).ready(function(){
	$(".datepicker").change(function() {
		gotoDate(0);
	});
});
</script>


		<h1 class="content_title" style="font-size:220%;">查詢 <small>日期</small></h1>
		<hr/>
		<div style="color:#2166e5; font-weight:bold; font-size: 1.7em;margin: 25px;text-align:center;">
			教室登記 / 查詢開放時間：
			@if($dateLimit['start']['all']=="2000-01-01")
				不開放
			@else
				{{$dateLimit['start']['all']." ~ ".$dateLimit['end']['all']}}
			@endif
		</div>
		<div style="width:45%;display:inline-block;margin:0 0 20px 30px">
			@foreach($announce as $item)
			<div class="round-div">
				{{$item->content}}
				<div style="text-align:right">{{$item->date}}</div>
			</div>
			@endforeach

			@if(count($key))
			<div class="round-div">
				<div style="font-size:20px;font-weight:700">尚未歸還鑰匙名單：</div><br/>
				<div style="font-size:15px">
				@foreach($key as $item)
					{{$item->date.' '.$item->classroom.' '.$item->username.'<br/>'}}
				@endforeach
				</div><br/>
				<div style="font-size:20px;font-weight:700">請盡速歸還，謝謝</div>
			</div>
			@endif

			@if(count($block))
			<div class="round-div">
				<div style="font-size:20px;font-weight:700">違規使用名單：</div><br/>
				<div style="font-size:15px">
				@foreach($block as $item)
					{{$item->student_id.' '.$item->name.'<br/>'}}
				@endforeach
				</div><br/>
				<div style="font-size:20px;font-weight:700">請盡速聯絡系辦，謝謝</div>
			</div>
			@endif
		</div>
		<div style="display:inline-block;width:50%;margin-top:25px;margin-right:30px;float:right;">
{{HTML::style('css/date/jquery-ui-1.10.1.css')}}
			<div class="datepicker ll-skin-lugo"></div>
		</div>
	
@stop
