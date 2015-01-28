@extends('layouts.navbar')
@section('content')

		<h1 class="content_title" style="font-size:220%;">登入 <small>學號</small></h1>
		<hr/>
		{{HTML::style('css/date/jquery-ui-1.10.1.css')}}		
		<div class="datepicker ll-skin-lugo" style="display: inline-block"></div>
		<div style="float:right;display:inline-block;text-align:right;font-size:18px;">
			Background: <input type="text" id="background"/> <br/>
			Title-Background: <input type="text" id = "titlebackground" /> <br/>
			Title-Font: <input type = "text" id="titlefont"/><br/>
			Week-Font: <input type="text" id ="weekfont"/><br/>
			Date-Font: <input type="text" id="datefont" /><br/>
			Date-Background: <input type="text" id ="datebackground" /> <br/>
			CurrentDate-Font: <input type="text" id="activefont" /><br/>
			CurrentDate-Background: <input type="text" id="activebackground" /><br/>
		</div>
<style>input{margin:3px; width: 100px;}</style>
<script>
$(document).ready(function(){
	Background = rgb2hex($(".ui-widget").css("background-color"));
	TitleBackground = rgb2hex($(".ui-datepicker-header").css("background-color"));
	TitleFont = rgb2hex($(".ui-datepicker-header").css("color"));
	WeekFont = rgb2hex($(".ui-datepicker th").css("color"));
	DateFont = rgb2hex($("td .ui-state-default:eq(10)").css("color"));
	DateBackground = rgb2hex($("td .ui-state-default:eq(10)").css("background-color"));
	ActiveFont = rgb2hex($("td .ui-state-active").css("color"));
	ActiveBackground = rgb2hex($("td .ui-state-active").css("background-color"));
	$("#background").val(Background);
	$("#titlebackground").val(TitleBackground);
	$("#titlefont").val(TitleFont);
	$("#weekfont").val(WeekFont);
	$("#datefont").val(DateFont);
	$("#datebackground").val(DateBackground);
	$("#activefont").val(ActiveFont);
	$("#activebackground").val(ActiveBackground);
});

$('#background').change(function(){
	tmp = $("#background").val();
	$(".ui-widget").css("background-color",tmp);
});

$('#titlebackground').change(function(){
	tmp = $("#titlebackground").val();
	$(".ui-datepicker-header").css("background-color",tmp);
});

$('#titlefont').change(function(){
	tmp = $("#titlefont").val();
	$(".ui-datepicker-header").css("color",tmp);
});

$('#weekfont').change(function(){
	tmp = $("#weekfont").val();
	$(".ui-datepicker th").css("color",tmp);
});

$('#datefont').change(function(){
	tmp = $("#datefont").val();
	$("td .ui-state-default").css("color",tmp);
});

$('#datebackground').change(function(){
	tmp = $("#datebackground").val();
	$("td .ui-state-default").css("background-color",tmp);
});

$('#activefont').change(function(){
	tmp = $("#activefont").val();
	$("td .ui-state-active").css("color",tmp);
});

$('#activebackground').change(function(){
	tmp = $("#activebackground").val();
	$("td .ui-state-active").css("background-color",tmp);
});

var hexDigits = new Array
        ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 
function rgb2hex(rgb) {
 rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
 return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}
function hex(x) {
  return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
 }
</script>
	
@stop
