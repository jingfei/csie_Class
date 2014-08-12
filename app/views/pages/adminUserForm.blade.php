@extends('layouts.navbar')
@section('content')

		<h1 class="content_title" style="font-size:220%;">新增修改 <small>使用者</small></h1>
		<hr/>
		<div id="dialog-form" style="width:340px;margin:0 auto;">
			{{ Form::open(array('class' => 'box', 'url' => $Url, 'method' => 'post', 'id' => 'classForm')) }}
			<fieldset class="boxBody">
			
			<label> 使用者帳號 </label>
			<input type="text" name="userid" @if($old) value="{{$old->userid}}" @endif required/>
			
			<label> 使用者名稱 </label>
			<input type="text" name="username" @if($old) value="{{$old->username}}" @endif required/>
			
			@if(!$old)
			<label> 使用者密碼 (請使用者登入後修改) </label>
			<input type="text" name="type" value="csie" readonly/>
			@endif
			
			</fieldset>
	
			<footer>
			<input type="submit" value="@if($old)更新 @else新增 @endif" class="btnLogin" tabindex="4" />
			</footer>
		{{ Form::close() }}
		</div>
		
@stop
