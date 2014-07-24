<nav>
	<ul>
		@if(Session::get('user')=='admin')
			<li id="news"><a href="/StudentCard/query" class="myButton">查詢</a></li>
		@endif
		@if(Session::has('user'))
			<li id="news"><a href="/StudentCard/Logout" class="myButton">logout</a></li>
		@endif
	</ul>
</nav>
