<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>CSIE Equipment Borrowing</title>

{{HTML::style('css/main.css')}}
{{HTML::style('css/Button.css')}}
{{HTML::style('css/class.css')}}
{{HTML::style('css/adminTable.css')}}
{{HTML::style('css/navbar/styles.css')}}
{{HTML::style('css/Login/reset.css')}}
{{HTML::style('css/Login/structure.css')}}
{{HTML::style('css/date/lugo.datepicker.css')}}
{{HTML::style('css/date/siena.datepicker.css')}}
<!-- Including the Lobster font from Google's Font Directory -->
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster" />
<!-- Enabling HTML5 support for Internet Explorer -->
<!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
{{HTML::script('js/jquery-1.9.1.js')}}
{{HTML::script('js/jquery-ui.js')}}
{{HTML::script('js/control.js')}}
<script>
	$(function() {	
		$( ".datepicker" ).datepicker({
			inline: true,
			showOtherMonths: true
		});
	});
</script>
