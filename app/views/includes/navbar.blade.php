<nav>
	<ul>
		@if(Session::get('user')=='admin')
			<li id="news"><a href="/Class2014/Admin" class="myButton">課程管理</a></li>
			<li id="news"><a href="/Class2014/adminSetting" class="myButton">設定</a></li>
		@endif
		<li id="news"><a href="/Class2014/" class="myButton">查詢</a></li>
		@if(Session::has('user'))
			<li id="news"><a href="/Class2014/Logout" class="myButton">logout</a></li>
		@else
			<li id="news"><a href="/Class2014/Login" class="myButton">login</a></li>
		@endif
	</ul>
</nav>
