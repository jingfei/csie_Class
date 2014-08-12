<nav>
	<ul>
		@if(Session::get('user')=='admin')
			<li><a href="/Class2014/adminUser" class="myButton">user管理</a></li>
			<li><a href="/Class2014/Admin" class="myButton">課程管理</a></li>
			<li><a href="/Class2014/adminSetting" class="myButton">設定</a></li>
		@elseif(Session::has('user'))
			<li><a href="/Class2014/" class="myButton">維護資料</a></li>
		@endif
		<li><a href="/Class2014/" class="myButton">查詢</a></li>
		@if(Session::has('user'))
			<li><a href="/Class2014/Logout" class="myButton">logout</a></li>
		@else
			<li><a href="/Class2014/Login" class="myButton">login</a></li>
		@endif
	</ul>
</nav>
