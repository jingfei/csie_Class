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
			<input type="text" value="{{$user}}" name="form_user" readonly/>
			
			@if(!$repeat)
			<label style="display:inline"> 日期 </label>
			@if(!$old)
			<!--新的資料才需要-->
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="form_repeat" value="Repeat" style="text-align:right;vertical-align:middle" onClick="ShowCycle(this);"/>連續借用
			@endif
			<input type="text" value="{{$year.' 年 '.$month.' 月 '.$day.' 日'}}" readonly/>
			<input type="hidden" name="date_start" value="{{date("Y-m-d", mktime(0,0,0,$month,$day,$year))}}" />
			@if(!$old)
			<!--新的資料才需要-->

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
			@endif
			@endif
			<label> 課程 / 活動名稱 </label>
			<input type="text" name="title" @if($old) value="{{$old->reason}}" @endif required/>
			
			<label> 教室 </label>
			<select name="form_class" id="form_class">
				@for($i=0; $i<count($data); $i++)
					@if($className==$data[$i]->name)
						<option selected="selected">{{ $data[$i]->name.' '.$data[$i]->type }}</option>
					@else
						<option>{{ $data[$i]->name.' '.$data[$i]->type }}</option>
					@endif
				@endfor
			</select>

			<label> 時間 </label>
			@if(!$old)
			<input type="hidden" name="time_start" value="{{$startTime}}" />
			<span id="time_start">{{$startTime}}</span>:00
			@else
			<select name="time_start">
				@for($i=8; $i<22; ++$i)
					<option @if($startTime==$i) selected="selected" @endif >{{ $i }}:00</option>
				@endfor
			</select>
			@endif
			~
			<select name="time_end" id="time_end">
				@for($i=9; $i<23; ++$i)
					@if($old || $i>= $endTime)
						<option @if($endTime==$i) selected="selected" @endif >{{ $i }}:00</option>
					@endif
				@endfor
			</select>
			
			<label> email </label>
			<input type="text" name="form_email" @if($old) value="{{$old->email}}" @endif required/>
			
			<label> 聯絡電話 </label>
			<input type="text" name="form_tel" @if($old) value="{{$old->phone}}" @endif required/>
			
			<label> 借用事由 </label>
			@foreach($reason as $type)
				<input type="radio" name="form_reason" value="{{$type->type}}" @if($old && $old->type==$type->id) checked @endif required/> {{$type->type}} &nbsp;&nbsp;
			@endforeach

			</fieldset>
	
			<footer>
			@if($old)
			<input type="hidden" name="old" value="{{$old->id}}" />
			@endif
			@if($repeat)
			<input type="hidden" name="old_repeat" value="{{$repeat}}" />
			@endif
			<input type="submit" value="@if($old)更新 @else借用 @endif" class="btnLogin" tabindex="4" />
			</footer>
		{{ Form::close() }}
		</div>
		
@stop
