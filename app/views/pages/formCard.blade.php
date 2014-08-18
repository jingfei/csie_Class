@extends('layouts.navbar')
@section('content')

		<h1 class="content_title" style="font-size:220%;">修改 <small>個人資料</small></h1>
		<hr/>
		{{ Form::open(array('class' => 'box login', 'url' => 'Modify', 'method' => 'post')) }}
		<fieldset class="boxBody styled-select">
		<span style="color:red">*請務必填寫正確資料</span><br/>
		
		<label> 姓名 (name) </label>
		<input type="text" tabindex = "1" name="name" value="{{$student?$student->name:''}}" required>
		
		<label> 學號 (student id) </label>
		<input type="text" tabindex = "2" name="studentid" value="{{$id}}" readonly>
		
		<label> 入學年份 (enrollment year) </label>
		<select name="dept" value="{{$student?$student->department:''}}" >
			<option @if($student && $student->department == '大學部') selected="selected" @endif>大學部</option>
			<option @if($student && $student->department == '資訊所') selected="selected" @endif>資訊所</option>
			<option @if($student && $student->department == '醫資所') selected="selected" @endif>醫資所</option>
			<option @if($student && $student->department == '製造所') selected="selected" @endif>製造所</option>
			<option @if($student && $student->department == '多媒學程') selected="selected" @endif}>多媒學程</option>
		</select>
		<select name="year">
			@for ($i=date("Y"); $i>=date("Y")-10; --$i)
				<option @if($student && $student->enrollment_year == $i) selected="selected" @endif> {{$i}} </option>
			@endfor
		</select>年入學
		
		<label> 學生證號 (student card id) </label>
		<input type="text" name="card" tabindex = "2" value="{{$student?$student->student_card:''}}" required>
		
		<label> 聯絡電話 (phone number) </label>
		<input type="text" name="number" tabindex = "3" value="{{$student?$student->phone:''}}" required>

		</fieldset>

		<footer>
		<input type="submit" value="Submit" class="btnLogin" tabindex="4">
		</footer>
		
		{{ Form::close() }}
		
@stop
