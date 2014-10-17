@extends('layouts.navbar')
@section('content')


{{HTML::style('css/date/jquery-ui.css')}}
{{HTML::script('js/csvData.js')}}


		<h1 class="content_title" style="font-size:220%;">教室大量借用 <small>管理者專用</small></h1>
		<hr/>
		<input type="hidden" id="AjaxUrl" value="{{URL::to('CheckClass')}}" />
		<table style="width:1000px" id="Table" class="bordered">
			<col width="30px" />
			<col width="220px" />
			<col width="200px" />
			<col width="150px" />
			<col width="170px" />
			<col width="230px" />
			<tr>
				<th></th>
				<th>日期 (間隔為一週)</th>
				<th>課程名稱</th>
				<th>教室</th>
				<th>時間</th>
				<th>狀態</th>
			</tr>
			@for($tr=1; $tr<=100; ++$tr)
			<tr class="ClassRow" id="Row-{{$tr}}" style="display:none">
				<td>{{$tr}}</td>
				<td><input type="text" 
						   class="DatePicker DateStart Form{{$tr}}" 
						   id="BigDateStart{{$tr}}" />
					<span id="DateTo{{$tr}}" style="display:none">~
						<input type="text" 
							   class="DatePicker Form{{$tr}}"
							   id="BigDateEnd{{$tr}}" />
					</span>
				</td>
				<td><input type="text" id="title{{$tr}}" class="Form{{$tr}}"/></td>
				<td>
					<select id="BigClass{{$tr}}" style="width:130px">
						@for($i=0; $i<count($data); $i++)
								<option>{{ $data[$i]->name.' '.$data[$i]->type }}</option>
						@endfor
					</select>
				</td>
				<td>
					<select class="TimeStart"
							id="BigTimeStart{{$tr}}"
							style="width:65px">
						@for($i=8; $i<22; ++$i)
								<option>{{$i}}:00</option>
						@endfor
					</select>
					~ 
					<select id="BigTimeEnd{{$tr}}" style="width:65px">
						@for($i=9; $i<=22; ++$i)
								<option>{{$i}}:00</option>
						@endfor
					</select>
				</td>
				<td id="Valid{{$tr}}"></td>
			</tr>
			@endfor
			<tr>
				<td colspan="6">
					<img id="Add" src="{{asset('img/list_add.png')}}" />
				</td>
			</tr>
		</table>
<style>
#Add{
	width:70px;
	cursor: pointer;
}
.DatePicker{
	width: 80px;
}
#Table{
	table-layout: fixed;
}
</style>

@stop
