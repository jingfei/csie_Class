@extends('layouts.navbar')
@section('content')

{{HTML::style('colorpicker/colorpicker.css')}}
{{HTML::style('colorpicker/layout.css')}}
{{HTML::script('colorpicker/colorpicker.js')}}
<script>
$(document).ready(function(){
	$('#colorSelector').ColorPicker({
		color: '#0000ff', //$('#colorSelector div').css('backgroundColor'),
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#colorSelector div').css('backgroundColor', '#' + hex);
			$('#color').val('#' + hex);
		}
	});
});
</script>

		<h1 class="content_title" style="font-size:220%;">新增修改 <small>借用事由</small></h1>
		<hr/>
		<div id="dialog-form" style="width:340px;margin:0 auto;">
			{{ Form::open(array('class' => 'box', 'url' => $Url, 'method' => 'post', 'id' => 'classForm')) }}
			<fieldset class="boxBody">
			
			<label> 事由名稱 </label>
			<input type="text" name="name" @if($old) value="{{$old->type}}" @endif required/>
			
			<label> 顏色 </label>
			<div id="colorSelector">
				<div style="background-color: @if($old) {{$old->color}} @else gray @endif"></div>
				<input type="text" name="color" id="color" value="@if($old) {{$old->color}} @else gray @endif" style="margin-left:40px;width:230px" readonly/>
			</div>
			
			</fieldset>
	
			<footer>
			<input type="submit" value="@if($old)更新 @else新增 @endif" class="btnLogin" tabindex="4" />
			</footer>
		{{ Form::close() }}
		</div>
		
@stop
