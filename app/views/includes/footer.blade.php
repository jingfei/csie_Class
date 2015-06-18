<hr style="width:100%"/>
<div id="loginform" class="modal">
	{{ Form::open(array('class' => 'box login', 'id' => 'LoginLogin')) }}
	<fieldset class="boxBody">
	
	<label> 實驗室帳號 / 資訊所學號 </label>
	<input type="text" tabindex = "1" name="studentid" id="inputStudentid"  required>
	
	<label> 密碼 (moodle) </label>
	<input type="password" tabindex="2" name="pw" id="inputPasswd" required>
	</fieldset>

	<footer>
	<input type="submit" value="Login" class="btnLogin" tabindex="4">
	</footer>
	
	{{ Form::close() }}
</div>
<div id="footer" style="color:#7f7f7f">
	<blockquote style="float: left"> 
		Copyright © 2014 國立成功大學資訊工程系<br/>
		70101 台南市東區大學路一號&nbsp;｜&nbsp;06-2757575 ext 62500&nbsp;
		<br/><br/>
	</blockquote>
	<blockquote style="float: right">
		<small>
			<br/>
			Designed by Jingfei
		</small>
		<br/><br/>
	</blockquote>
</div>
