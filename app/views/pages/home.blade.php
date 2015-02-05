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
		<div style="width:45%;display:inline-block;margin-bottom:20px">
			<div class="round-div">
				<h2 style="font-size:20px;color:red;line-height:1.2em;">2015/2/18 - 2015/2/22 <br/>(過年期間) 不開放借用</h2>
			</div>
			<div class="round-div">
				<h2 style="font-size:20px">各位借教室的同學請注意：</h2>
				<br/>
				<p style="font-size:15px">
				教室登記後不代表借鑰匙，請記得到系辦登記押證件，切勿自行拿走鑰匙未登記，<br/>
				使用完畢請將鑰匙送回系辦或投4215系辦的信箱，
				若未依時間內歸還(上班日隔天早上8:00)，<br/>
				<br/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;借用人 勞動服務1小時 <br/>
				累犯第二次 勞動服務3小時 <br/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第三次 勞動服務5小時... <br/>
					以此類推，<br/>
				<br/>
				敬請大家注意，謝謝！
				</p>
			</div>
			<div class="round-div">
				<h2 style="font-size:20px">尚未歸還鑰匙名單：</h2><br/>
				<p style="font-size:15px">
				@foreach($key as $item)
					{{$item->date.' '.$item->classroom.' '.$item->username.'<br/>'}}
				@endforeach
				</p><br/>
				<h2 style="font-size:20px">請盡速歸還，謝謝</h2>
			</div>
		</div>
		<div style="display:inline-block;width:50%;margin:25px;float:right;">
{{HTML::style('css/date/jquery-ui-1.10.1.css')}}
			<div class="datepicker ll-skin-lugo"></div>
		</div>
	
@stop
