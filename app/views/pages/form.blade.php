@extends('layouts.navbar')
@section('content')
<script>
$(document).ready(function(){
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

		<h1 class="content_title" style="font-size:220%;">新增修改 <small>教室資料</small></h1>
		<hr/>
		<div id="dialog-form" style="width:340px;margin:0 auto;">
			{{ Form::open(array('class' => 'box', 'url' => 'borrow', 'method' => 'post', 'id' => 'classForm')) }}
			<fieldset class="boxBody">
			
			<label> 借用者 </label>
			<input type="text" value="{{Session::get('user')}}" name="form_user" readonly/>
			
			<label style="display:inline"> 日期 </label>
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="form_repeat" value="Repeat" style="text-align:right;vertical-align:middle" onClick="ShowCycle(this);"/>連續借用
			<input type="text" value="{{$year.' 年 '.$month.' 月 '.$day.' 日'}}" readonly/>
			<input type="hidden" name="date_start" value="{{date("Y-m-d", mktime(0,0,0,$month,$day,$year))}}" />

			<div class="Repeat" style="display:none;margin:10px;padding:5px;border:1px dashed black;">
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
					@if(Input::get('className')==$data[$i]->name)
						<option selected="selected">{{ $data[$i]->name.' '.$data[$i]->type }}</option>
					@else
						<option>{{ $data[$i]->name.' '.$data[$i]->type }}</option>
					@endif
				@endfor
			</select>

			<label> 時間 </label>
			<input type="hidden" name="time_start" val="{{Input::get('startTime')}}" />
			<span id="time_start">{{Input::get('startTime')}}</span>:00
			~
			<select name="time_end" id="time_end">
				@for($i=9; $i<23; $i++)
					@if($i>= Input::get('endTime'))
					<option>{{ $i }}:00</option>
					@endif
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
		
@stop
