<!DOCTYPE html>
<html>
<head>
	@include('includes.head')
</head>

<body>
<!--h2><a href="http://tutorialzine.com/2011/05/css3-animated-navigation-menu/">&laquo; Read and download on Tutorialzine</a></h2-->
<div class="container">
	<div class="header">
		
		@include('includes.header')
		<div id="navbar">
			@include('includes.navbar')
		</div>
	</div>

	<div id="main" class="row">

		<div id="content">
			@yield('content')
		</div>
	
	</div>
	@include('includes.footer')
</div>
</body>
</html>
