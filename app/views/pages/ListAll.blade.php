@extends('layouts.navbar')
@section('content')

<style>
#Query{
	font-size:1.3em;
	border:1px dotted gray;
	width:45%;
	padding:5px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px; /* future proofing */
	-khtml-border-radius: 10px; /* for old Konqueror browsers */
}

#Query td{
	padding: 3px;
	vertical-align: middle;
}

</style>
<script>
function changeBlock($id, $block){
	var request = $.ajax({
		url: '{{URL::to('blockState')}}',
		type: "POST",
		data: {id: $id, block: $block}
	});

	request.fail(function( jqXHR, textStatus){
		alert("無法更新: "+textStatus);
	});
}
</script>


		<h1 class="content_title" style="font-size:220%;">學生證資料 <small>管理者專用</small></h1>
		<hr/>
<div id="Query"> 
	<table>
		<tr>
			<form action="query" method="get"> 
		<td>
			系所別
		</td>
		<td>
			<select name="dept">
			<option>不限</option>
			@foreach($Dept as $members)
				<option>{{$members->department}}</option>
			@endforeach
			</select>
			&nbsp;
			<select name="year">
				<option>不限</option>
			@foreach($Year as $members)
				<option>{{$members->enrollment_year}}</option>
			@endforeach
			</select>
			年入學
		</td>
		<td>
			<input type="submit" value="查詢" />
		</td>
			</form>
		</tr>
		<tr>
			<form action="addnew" method="get"> 
		<td>
			學號
		</td>
		<td>
			<input type="text" name="new" />
		</td>
		<td>
			<input type="submit" value="查詢或新增" />
		</td>
			</form>
		</tr>
		<tr>
			<form action="queryName" method="get"> 
		<td>
			姓名
		</td>
		<td>
			<input type="text" name="new" />
		</td>
		<td>
			<input type="submit" value="查詢" />
		</td>
			</form>
		</tr>
		<tr>
			<form action="queryCard" method="get"> 
		<td>
			學生證號
		</td>
		<td>
			<input type="text" name="new" />
		</td>
		<td>
			<input type="submit" value="查詢" />
		</td>
			</form>
		</tr>
		<tr>
			<form action="blockList" method="get">
			<td></td>
			<td></td>
			<td>
				<input type="submit" value="查詢無法使用名單"/>
			</td>
			</form>
		</tr>
	</table>
</div>
@if($data)
<div>
	<table class="bordered" style="margin:5px 0;">
		<thead>
		<tr>
			<th>學號</th>
			<th>姓名</th>
			<th>系所</th>
			<th>入學年份</th>
			<th>學生證號碼</th>
			<th>手機號碼</th>
			<th>可否使用</th>
			<th>修改</th>
			<th>刪除</th>
		</tr>
		</thead>
		<tbody>
	@foreach($data as $item)
		<tr>
			<td>{{$item->student_id}}</td>
			<td>{{$item->name}}</td>
			<td>{{$item->department}}</td>
			<td>{{$item->enrollment_year}}</td>
			<td>{{$item->student_card}}</td>
			<td>{{$item->phone}}</td>
			<td style="width:100px;">
				<input type="radio" name="{{$item->id}}" id="yes-{{$item->id}}" class="yes" @if(!$item->block) checked @endif />
				<input type="radio" name="{{$item->id}}" id="no-{{$item->id}}" class="no" @if($item->block) checked @endif />
				<div class="switch">
					<label for="yes-{{$item->id}}" class="Lyes" onClick="changeBlock({{$item->id}},0);">Yes</label>
					<label for="no-{{$item->id}}" class="Lno" onClick="changeBlock({{$item->id}},1);">No</label>
					<span></span>
				</div>
			</td>
			<td class="Modify"><img src="img/Modify.png" onClick="location.href='./form_admin/{{Input::get('dept')}}/{{Input::get('year')}}/{{$item->student_id}}'"/></td>
			<td class="Delete"><img src="img/Delete.png" onClick="if(confirm('確定刪除？')) location.href='./form_delete/{{Input::get('dept')}}/{{Input::get('year')}}/{{$item->student_id}}'" /></td>
		</tr>
	@endforeach
		<tr>
			<td colspan="9" style="text-align:right">
			@if($CSV)
			<input type="button" value="下載" onClick="location.href='DownloadCSV';" />
			@endif
			</td>
		</tr>
		</tbody>
	</table>
</div>
	</form>
@endif
@stop

