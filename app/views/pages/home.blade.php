@extends('layouts.navbar')
@section('content')

<style>
.round-div{
	border:1px solid gray;
	color: #0e2d66;
	padding:10px;
	float:left;
	margin:5px;
	width:90%;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px; /* future proofing */
	-khtml-border-radius: 10px; /* for old Konqueror browsers */
}
</style>


		<h1 class="content_title" style="font-size:220%;">查詢 <small>日期</small></h1>
		<hr/>
		<div style="width:30%">
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
		<div style="display:inline-block;width:68%">
			<div style="text-align:center;">
				<div style="color:#2166e5; font-weight:bold; font-size: 1.5em;margin: 10px">
					教室登記 / 查詢開放時間：
					@if($dateLimit['start']['all']=="2000-01-01")
						不開放
					@else
						{{$dateLimit['start']['all']." ~ ".$dateLimit['end']['all']}}
					@endif
				</div>
				<button class="innerButton" onClick="ClickToday()" >today</button>
				<button class="innerButton" onClick="gotoDate(0)">選擇</button>
			</div>
{{HTML::style('css/date/jquery-ui-1.10.1.css')}}
			<div class="datepicker ll-skin-lugo"></div>
		</div>
	
@stop
