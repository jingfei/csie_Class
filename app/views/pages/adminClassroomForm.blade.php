@extends('layouts.navbar')
@section('content')

		<h1 class="content_title" style="font-size:220%;">新增修改 <small>教室異動</small></h1>
		<hr/>
		<div id="dialog-form" style="width:340px;margin:0 auto;">
			{{ Form::open(array('class' => 'box', 'url' => $Url, 'method' => 'post', 'id' => 'classForm')) }}
			<fieldset class="boxBody">
			
			<label> 教室名稱 </label>
			<input type="text" name="name" @if($old) value="{{$old->name}}" @endif required/>
			
			<label> 教室類別 </label>
			<input type="text" name="type" @if($old) value="{{$old->type}}" @endif required/>
			
			</fieldset>
	
			<footer>
			<input type="submit" value="@if($old)更新 @else新增 @endif" class="btnLogin" tabindex="4" />
			</footer>
		{{ Form::close() }}
		</div>
		
@stop
