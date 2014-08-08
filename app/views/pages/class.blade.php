@extends('layouts.navbar')
@section('content')

<script>
$(document).ready(function(){
	var currentDate = $( ".datepicker" ).datepicker( "setDate", "{{$year."/".$month."/".$day}}" );

	$(".datepicker").change(function() {
		var nowDate = $( ".datepicker" ).datepicker( "getDate" );
		gotoDate();
	});
	initClassform();
	/*自動選取*/
	$('.repeat1').on('change', function(){
		$('#repeat1').prop('checked', true);
	});
	$('.repeat2').on('change', function(){
		$('#repeat2').prop('checked', true);
	});
	/**********/
});

</script>


		<h1 class="content_title" style="font-size:220%;">查詢 <small>教室</small></h1>
		<hr/>
		<!-- form-start -->
		<div id="dialog-form" title="class">
			{{ Form::open(array('class' => 'box', 'url' => 'borrow', 'method' => 'post', 'id' => 'classForm')) }}
			<fieldset class="boxBody">
			
			<label> 借用者 </label>
			<input type="text" value="haha" name="form_user" readonly/>
			
			<label style="display:inline"> 日期 </label>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="form_repeat" value="Repeat" style="text-align:right;vertical-align:middle" onClick="ShowCycle(this);"/>連續借用
			<input type="text" value="{{$year.' 年 '.$month.' 月 '.$day.' 日'}}" readonly/>
			<input type="hidden" name="date_start" value="{{date("Y-m-d", mktime(0,0,0,$month,$day,$year))}}" />

			<div class="Repeat" style="display:none;padding:10px;">
				間隔及循環方式:
				<br/>
				&nbsp;&nbsp;每隔&nbsp;
				<select name="date_interval">
					@for($i=1; $i<7; ++$i)
					<option>{{$i}}</option>
					@endfor
				</select>
				&nbsp;
				<select name="date_intervalUnit">
					<option>天</option>
					<option selected="selected">週</option>
					<option>月</option>
				</select><br/><br/>
				
				循環次數:<br/>
				&nbsp;&nbsp;<input type="radio" name="Repeat_end" value="occurence" id="repeat1" checked required/>
					<select name="date_num" class="repeat1">
						@for($i=1; $i<=20; $i++)
						<option>{{$i}}</option>
						@endfor
					</select>
					個循環後結束<br/>
				<!-- date_start -->
				&nbsp;&nbsp;<input type="radio" name="Repeat_end" value="date" id="repeat2" required/>
				直到
				<select name="date_year" class="repeat2">
					<option @if(date("Y")==$year) selected="selected" @endif>
						{{date("Y")}}
					</option>
					<option @if(date("Y")+1==$year) selected="selected" @endif>
						{{date("Y")+1}}
					</option>
				</select>
				年
				<select name="date_month" class="repeat2">
					@for($i=1; $i<=12; ++$i)
						<option @if($month==$i) selected="selected" @endif>
							{{$i}}
						</option>
					@endfor
				</select>
				月
				<select name="date_day" class="repeat2">
					@for($i=1; $i<=date('d',mktime(0,0,0,$month,0,$year)); ++$i)
						<option @if($day==$i) selected="selected" @endif>
							{{$i}}
						</option>
					@endfor
				</select>
				日
				<br/>
				<!-- date_end -->
			</div>
			
			<label> 課程 / 活動名稱 </label>
			<input type="text" name="title" required/>
			
			<label> 教室 </label>
			<select name="form_class" id="form_class">
				@for($i=0; $i<count($data); $i++)
				<option>{{ $data[$i]->name.' '.$data[$i]->type }}</option>
				@endfor
			</select>

			<label> 時間 </label>
			<input type="hidden" name="time_start" id="time_start_hidden" />
			<span id="time_start"></span>:00
			~
			<select name="time_end" id="time_end">
				@for($i=9; $i<23; $i++)
				<option>{{ $i }}:00</option>
				@endfor
			</select>
			
			<label> email </label>
			<input type="text" name="form_email" required/>
			
			<label> 聯絡電話 </label>
			<input type="text" name="form_tel" required/>
			
			<label> 借用事由 </label>
			<input type="radio" name="form_reason" value="課程" checked required/>課程 &nbsp;&nbsp;
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
							@if($table[$time-8][$i][0]==-1)
							<td class="class_inner no_event" name="{{$time.';'.$data[$i-1]->name}}">
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

