@extends('layouts.navbar')
@section('content')

		<h1 class="content_title" style="font-size:220%;">登入 <small>學號</small></h1>
		<hr/>
		{{ Form::open(array('class' => 'box login', 'url' => 'log_in', 'method' => 'post')) }}
		<fieldset class="boxBody">
		
		<label> 實驗室帳號 / 學號 </label>
		<input type="text" tabindex = "1" name="studentid" required>
		
		<label> 密碼 (moodle) </label>
		<input type="password" tabindex="2" name="pw" required>
		</fieldset>

		<footer>
		<input type="submit" value="Login" class="btnLogin" tabindex="4">
		</footer>
		
		{{ Form::close() }}
	
@stop
