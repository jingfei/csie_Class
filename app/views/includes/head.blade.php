<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="keywords" content="成大資工,鑰匙,設備,鑰匙借用">
<meta http-equiv="description" content="成功大學資訊工程學系 設備及鑰匙借用系統，供系上實驗室及學生查詢及登記借用鑰匙">

<title>CSIE Equipment Borrowing</title>

<link rel="shortcut icon" href="{{asset('img/csie.ico')}}" type="image/x-icon" />
{{HTML::style('css/main.css')}}
{{HTML::style('css/Button.css')}}
{{HTML::style('css/class.css')}}
{{HTML::style('css/adminTable.css')}}
{{HTML::style('css/navbar/styles.css')}}
{{HTML::style('css/Login/reset.css')}}
{{HTML::style('css/Login/structure.css')}}
{{HTML::style('css/cardRadio.css')}}
{{HTML::style('css/date/lugo.datepicker.css')}}
{{HTML::style('css/date/siena.datepicker.css')}}
{{HTML::style('css/jquery-te-1.4.0.css')}}
{{HTML::style('css/jquery.modal.css')}}
<!-- Including the Lobster font from Google's Font Directory -->
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster" />
<!-- Enabling HTML5 support for Internet Explorer -->
<!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
{{HTML::script('js/jquery-1.9.1.js')}}
{{HTML::script('js/jquery-ui.js')}}
{{HTML::script('js/control.js')}}
{{HTML::script('js/jquery-te-1.4.0.min.js')}}
{{HTML::script('js/jquery.modal.min.js')}}
<script>
	$(function() {	
		$( ".datepicker" ).datepicker({
			inline: true,
			showOtherMonths: true
		});
	});
</script>
