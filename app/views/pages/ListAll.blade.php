@extends('layouts.navbar')
@section('content')
		<h1 class="content_title" style="font-size:220%;">查詢 <small>個人資料</small></h1>
		<hr/>
<div id="Query">
	<form action="query" method="get"> 
	系所別：
	<select name="dept">
		<option>不限</option>
	@foreach($Dept as $members)
		<option>{{$members->department}}</option>
	@endforeach
	</select>
	&nbsp; &nbsp;
	<select name="year">
		<option>不限</option>
	@foreach($Year as $members)
		<option>{{$members->enrollment_year}}</option>
	@endforeach
	</select>
	年入學
	<input type="submit" value="查詢" />
	</form>
	<form action="addnew" method="get"> 
	學號：
	<input type="text" name="new" />
	<input type="submit" value="查詢或新增" />
	</form>
	<form action="queryCard" method="get"> 
	學生證號：
	<input type="text" name="new" />
	<input type="submit" value="查詢" />
	</form>
</div>
@if($data)
<div id="QueryResult">
	<table id="QueryTable">
		<thead>
		<tr>
			<th>學號</th>
			<th>姓名</th>
			<th>系所</th>
			<th>入學年份</th>
			<th>學生證號碼</th>
			<th>手機號碼</th>
			<th>修改</th>
			<th>刪除</th>
		</tr>
		</thead>
		<tbody>
	@foreach($data as $student)
		<tr>
			@foreach($student as $i)
				<td>{{$i}}</td>
			@endforeach
			<td class="Modify"><img src="img/Modify.png" onClick="location.href='./form_admin/{{Input::get('dept')}}/{{Input::get('year')}}/{{$student->student_id}}'"/></td>
			<td class="Delete"><img src="img/Delete.png" onClick="if(confirm('確定刪除？')) location.href='./form_delete/{{Input::get('dept')}}/{{Input::get('year')}}/{{$student->student_id}}'" /></td>
		</tr>
	@endforeach
		<tr>
			<td colspan="8" style="text-align:right">
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

