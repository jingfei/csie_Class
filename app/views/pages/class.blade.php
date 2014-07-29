@extends('layouts.navbar')
@section('content')

<script>
$(document).ready(function(){
	var currentDate = $( ".datepicker" ).datepicker( "setDate", "{{$year."/".$month."/".$day}}" );

	$(".datepicker").change(function() {
		var nowDate = $( ".datepicker" ).datepicker( "getDate" );
		gotoDate();
	});
	$( "#dialog-form" ).dialog({ autoOpen: false });
	$( ".inner_div" ).click(function() {
		  $( "#dialog-form" ).dialog( "open" );
	});
	$( "#dialog-form" ).dialog({ closeText: "" });
	$( "#dialog-form" ).dialog({ modal: true });
	$( "#dialog-form" ).dialog({ show: true });
	$( "#dialog-form" ).dialog({ hide: { effect: "explode", duration: 500 } });
	$( "#dialog-form" ).dialog({ width: 340 });
	$( "#dialog-form" ).dialog({ height: 650 });
});

</script>


		<h1 class="content_title" style="font-size:220%;">查詢 <small>教室</small></h1>
		<hr/>
		<!-- form-start -->
		<div id="dialog-form" title="class">
			{{ Form::open(array('class' => 'box login', 'url' => 'Login', 'method' => 'post')) }}
			<fieldset class="boxBody">
			
			<label> 借用者 </label>
			<input type="text" value="haha" readonly/>
			
			<label> 課程 / 活動名稱 </label>
			<input type="text" name="title" required/>
			
			<label> 日期 </label>
			<input type="date" name="date_start" required>
			<br/>
			<input type="checkbox" name="form_repeat" value="Repeat" onClick="if(this.checked) $('.Repeat').show(); else $('.Repeat').hide();"/>連續借用
			<div class="Repeat" style="display:none">
				循環方式: 
				<select>
					<option>每天</option>
					<option selected="selected">每週</option>
					<option>每月</option>
				</select><br/><br/>
				
				間隔:
				每隔 <br/><br/>
				
				循環次數:<br/>
				<input type="radio" name="Repaet_end" value="occurence" required/>
					<select>
						@for($i=1; $i<=20; $i++)
						<option>{{$i}}</option>
						@endfor
					</select>
					個循環後<br/>
				<input type="radio" name="Repaet_end" value="date" required/>
				<input type="date" name="date_end" /><br/>
			</div>
			
			<label> 教室 </label>
			<select name="form_class">
				@for($i=0; $i<count($data); $i++)
				<option>{{ $data[$i] }}</option>
				@endfor
			</select>

			<label> 時間 </label>
			<select name="time_start">
				@for($i=8; $i<22; $i++)
				<option>{{ $i }}:00</option>
				@endfor
			</select>
			~
			<select name="time_end">
				@for($i=9; $i<23; $i++)
				<option>{{ $i }}:00</option>
				@endfor
			</select>
			
			<label> email </label>
			<input type="text" name="form_email" required/>
			
			<label> 聯絡電話 </label>
			<input type="text" name="form_tel" required/>
			
			<label> 借用事由 </label>
			<input type="radio" name="form_reason" value="課程" required/>課程 &nbsp;&nbsp;
			<input type="radio" name="form_reason" value="會議" required/>會議 &nbsp;&nbsp;
			<input type="radio" name="form_reason" value="活動" required/>活動 &nbsp;&nbsp;

			</fieldset>
	
			<footer>
			<input type="submit" value="借用" class="btnLogin" tabindex="4">
			</footer>
			
		{{ Form::close() }}
		</div>
		<!-- form-end -->
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
							<td class="class_inner no_event">
								<div class="outer_div">
								<div class="inner_div">
								</div></div>
							</td>
							@elseif($table[$time-8][$i][0]==1)
							<td class="class_inner">
								<div class="outer_div">
								<div class="inner_div">
									<br/>
									{{$table[$time-8][$i][1]}}<br/>
									<small>{{$table[$time-8][$i][2]}}</small>
								</div></div>
							</td>
							@elseif($table[$time-8][$i][0]>1)
							<td class="class_inner" rowspan="{{$table[$time-8][$i][0]}}">
								<div class="outer_div">
								<div class="inner_div">
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

