@extends('layouts.navbar')
@section('content')

<style>
td{vertical-align:middle;}
.nav {
	margin-left: 0;
	list-style: none;
	font-size: 20px;
	white-space: nowrap;
	float: left;
}
.nav-tabs {
	border-bottom: 1px solid #ddd;
}
.nav-tabs>li {
	float: left;
	margin-bottom: -3px;
}
.nav-tabs>.active>a {
	color: #555555;
	border: 1px solid #ddd;
	border-bottom-color: transparent;
	cursor: default;
}
.nav-tabs>li>a {
	cursor: pointer;
	padding: 5px 12px;
	margin-right: 2px;
	line-height: 14px;
}
.float-scroll {
	position: fixed;
	top: 0;
}
</style>
{{HTML::style('css/key.css')}}
<script>
function adminQuery(){
	/*date1*/
	var y1 = $('#y1').val();
	var m1 = $('#m1').val();
	if(m1.length==1) m1 = '0'+m1; //兩位數
	var d1 = $('#d1').val();
	if(d1.length==1) d1 = '0'+d1; //兩位數
	var date1 = y1+"-"+m1+"-"+d1;
	/*******/
	/*date2*/
	var y2 = $('#y2').val();
	if(!y2) y2=y1;
	var m2 = $('#m2').val();
	if(!m2) m2=m1;
	if(m2.length==1) m2 = '0'+m2; //兩位數
	var d2 = $('#d2').val();
	if(d2.length==1) d2 = '0'+d2; //兩位數
	var date2 = y2+"-"+m2+"-"+d2;
	if(!d2) date2 = date1; 
	/*******/
	var Class = $('#adminClass').val();
	if(!Class) Class=0;
	var User = $('#adminUser').val();
	var Url = "/Class2014/adminKey/"+date1+"/"+date2;
	if(Class!==null) Url += "/"+Class;
	if(User) Url += "/"+User;
	location.href = Url;
}

$(document).ready(function(){
	$('input[type=radio]').change(function(){
		var state = $(this).attr('id').split('-')[1];
		var id = $(this).attr('name');
		var request = $.ajax({
			url: '{{URL::to('keyState')}}',
			type: "POST",
			data: {id: id, state: state}
		});

		request.fail(function( jqXHR, textStatus){
			alert("無法更新: "+textStatus);
		});
	});
});

var NavTop,Nav;
window.onload = function(){
	Nav=document.getElementById('Fauc-nav');
	var tmp1=Nav.getElementsByTagName('ul')[0];
	var tmp2=document.getElementById('Fauc-content');
	NavTop=tmp2.offsetTop+tmp1.offsetHeight;
	Nav.style.width=Nav.offsetWidth+"px";
	tmp1.style.width=Nav.offsetWidth-25+"px";
}
window.onscroll = function(){
	var t=document.documentElement.scrollTop || document.body.scrollTop;
	if(t>NavTop){
		Nav.className="float-scroll";
	}
	else{
		Nav.className="";
	}
}

function Active(obj, tab){
	$('.bordered').hide(); 
	$('#'+tab).show(); 
	$('.nav-tabs>li').attr('class', ''); 
	$(obj).parent().attr('class', 'active');
}
</script>	

		<h1 class="content_title" style="font-size:220%;">鑰匙管理 <small>管理者專用</small></h1>
		<hr/>
		<div style="font-size:1.3em">
			<div style="margin:10px">
				<div style="text-align:right;margin:8px 0;border:1px dotted #b0cdcb;padding:5px;line-height:2em;">
					<form>
					<input type="text" size="4" id="y1" value="{{$date1['year']}}" />年
					<input type="text" size="2" id="m1" value="{{$date1['month']}}"/>月
					<input type="text" size="2" id="d1" value="{{$date1['day']}}"/>日
					~
					<input type="text" size="4" id="y2" value="{{$date2['year']}}"/>年
					<input type="text" size="2" id="m2" value="{{$date2['month']}}"/>月
					<input type="text" size="2" id="d2" value="{{$date2['day']}}"/>日
					&nbsp;
					教室編號 <input type="text" size="5" id="adminClass" placeholder="不限" 
					@if($Class) value="{{$Class}}" @endif />
					&nbsp;
					借用者 <input type="text" size="15" id="adminUser" placeholder="不限" 
					@if($User) value="{{$User}}" @endif />
					&nbsp;
					<button type="submit" onClick="adminQuery();return false;">查詢</button>
					</form>
					<button type="submit" onClick="location.href='{{URL::to('allnoKey')}}';">查詢所有未歸還鑰匙名單</button>
				</div>
				<div style="overflow:auto;height:600px;">
					<div id="Fauc-nav" style="width:100%;line-height:1.8em;font-size:1.5em;">
						<ul class="nav nav-tabs" style="padding-left:20px;">
							<li class="active"; style="display:list-item;list-style:none">
								<a href="#" onClick="Active(this,'tab1');" data-toggle="tab">借出</a>
							</li>
							<li style="display:list-item;list-style:none">
								<a href="#" onClick="Active(this,'tab2');" data-toggle="tab">歸還</a>
							</li>
							<li style="display:list-item;list-style:none;">
								<a href="#" onClick="Active(this,'tab3');" data-toggle="tab">未借用</a>
							</li>
						</ul>
					</div>
					<table class="bordered" id="tab1" style="width:1500px;">
					@if(count($list2))
					<tr>
						<th>日期</th>
						<th>教室編號</th>
						<th>時間</th>
						<th style="width:150px">課程 / 活動名稱</th>
						<th>借用者</th>
						<th><img src="{{URL::to('img/key.ico')}}" style="width:30px;vertical-align:middle"/></th>
						<th>email</th>
						<th>聯絡電話</th>
					</tr>
					@foreach($list2 as $item)
					<tr>
						<td>{{$item->date}}</td>
						<td>{{$item->classroom}}</td>
						<td>{{$item->start_time}}:00~{{$item->end_time}}:00</td>
						<td>{{$item->reason}}</td>
						<td>{{$item->username}}</td>
						<td class="key">
							<div>
							<input type="radio" name="{{$item->id}}" id="{{$item->id}}-1" class="radio"/>
							<label for="{{$item->id}}-1">未借用</label>
							</div>
							<div>
							<input type="radio" name="{{$item->id}}" id="{{$item->id}}-2" class="radio" checked/>
							<label for="{{$item->id}}-2">借出</label>
							</div>
							<div>
							<input type="radio" name="{{$item->id}}" id="{{$item->id}}-3" class="radio"/>
							<label for="{{$item->id}}-3">歸還</label>
							</div>
						</td>
						<td>{{$item->email}}</td>
						<td>{{$item->phone}}</td>
					</tr>
					@endforeach
					@else
					<tr><td>
					查無資料
					</td></tr>
					@endif
					</table>
					<table class="bordered" id="tab2" style="width:1500px;display:none">
					@if(count($list3))
					<tr>
						<th>日期</th>
						<th>教室編號</th>
						<th>時間</th>
						<th style="width:150px">課程 / 活動名稱</th>
						<th>借用者</th>
						<th><img src="{{URL::to('img/key.ico')}}" style="width:30px;vertical-align:middle"/></th>
						<th>email</th>
						<th>聯絡電話</th>
					</tr>
					@foreach($list3 as $item)
					<tr>
						<td>{{$item->date}}</td>
						<td>{{$item->classroom}}</td>
						<td>{{$item->start_time}}:00~{{$item->end_time}}:00</td>
						<td>{{$item->reason}}</td>
						<td>{{$item->username}}</td>
						<td class="key">
							<div>
							<input type="radio" name="{{$item->id}}" id="{{$item->id}}-1" class="radio"/>
							<label for="{{$item->id}}-1">未借用</label>
							</div>
							<div>
							<input type="radio" name="{{$item->id}}" id="{{$item->id}}-2" class="radio"/>
							<label for="{{$item->id}}-2">借出</label>
							</div>
							<div>
							<input type="radio" name="{{$item->id}}" id="{{$item->id}}-3" class="radio" checked/>
							<label for="{{$item->id}}-3">歸還</label>
							</div>
						</td>
						<td>{{$item->email}}</td>
						<td>{{$item->phone}}</td>
					</tr>
					@endforeach
					@else
					<tr><td>
					查無資料
					</td></tr>
					@endif
					</table>
					<table class="bordered" id="tab3" style="width:1500px;display:none">
					@if(count($list1))
					<tr>
						<th>日期</th>
						<th>教室編號</th>
						<th>時間</th>
						<th style="width:150px">課程 / 活動名稱</th>
						<th>借用者</th>
						<th><img src="{{URL::to('img/key.ico')}}" style="width:30px;vertical-align:middle"/></th>
						<th>email</th>
						<th>聯絡電話</th>
					</tr>
					@foreach($list1 as $item)
					<tr>
						<td>{{$item->date}}</td>
						<td>{{$item->classroom}}</td>
						<td>{{$item->start_time}}:00~{{$item->end_time}}:00</td>
						<td>{{$item->reason}}</td>
						<td>{{$item->username}}</td>
						<td class="key">
							<div>
							<input type="radio" name="{{$item->id}}" id="{{$item->id}}-1" class="radio" checked/>
							<label for="{{$item->id}}-1">未借用</label>
							</div>
							<div>
							<input type="radio" name="{{$item->id}}" id="{{$item->id}}-2" class="radio"/>
							<label for="{{$item->id}}-2">借出</label>
							</div>
							<div>
							<input type="radio" name="{{$item->id}}" id="{{$item->id}}-3" class="radio"/>
							<label for="{{$item->id}}-3">歸還</label>
							</div>
						</td>
						<td>{{$item->email}}</td>
						<td>{{$item->phone}}</td>
					</tr>
					@endforeach
					@else
					<tr><td>
					查無資料
					</td></tr>
					@endif
					</table>
				</div>
			</div>
		</div>
	
@stop
