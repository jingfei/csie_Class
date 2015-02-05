<nav>
	<ul>
<?php 
	$permission="000000";
	if(Session::has('permission')) $permission=Session::get('permission');
?>
		<li><a href="/Class2014/" class="myButton">回首頁</a></li>
		@if($permission[0])
			<li><a href="/Class2014/query" class="myButton">學生證</a></li>
		@endif
		@if($permission[1])
			<li><a href="/Class2014/adminKey" class="myButton">鑰匙</a></li>
		@endif
		@if($permission[2])
			<li><a href="/Class2014/adminUser" class="myButton">使用者</a></li>
		@endif
		@if($permission[3])
			<li><a href="/Class2014/Admin" class="myButton">課程</a></li>
		@endif
		@if($permission[4])
			<li><a href="/Class2014/adminSetting" class="myButton">設定</a></li>
		@endif
		@if($permission[5])
			<li><a href="/Class2014/Personal" class="myButton">維護資料</a></li>
		@endif
		@if(Session::has('user'))
			<li><a href="/Class2014/Logout" class="myButton">logout</a></li>
		@else
			<li><a href="#" class="myButton" id="Loginbutton">login</a></li>
		@endif
		<li></li>
	</ul>
</nav>
