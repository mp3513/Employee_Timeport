<?php 
error_reporting(0);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Welcome to Acro TimePort </title>
<link rel="stylesheet" href="template.css" type="text/css" />
<script type="text/javascript" src="js/jquery.min.js"></script>

<script type="text/javascript">


$(function() {
  $("#userId").focus();
});





function PRFEnableDisable(){
	
	if(document.getElementById('enabledisable').checked){	 
	   var nodes = document.getElementById("finalid").getElementsByTagName('*');
   for(var i = 0; i < nodes.length; i++)
{
     nodes[i].disabled = true;
}
	   }
	   else {
	    document.getElementById('login_button').disabled=false;
	      
	   }
	   
	}
	


window.history.forward(1);
function noBack(){ 
	window.history.forward(); 
}
</script>

<script type="text/javascript">
$(function(){

$("#login_button").click(function() {
	if($("#userId").val() == ""){					
		$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b> Please Enter User Id</span>");	
		$('#userId').focus();				
		return false;
	}								
	if($("#password").val() == ""){
		$("#ErrorMsg").html("<span class='error_msg' ><b>Error :</b> Please Enter Password</span>");					
		$('#password').focus();
		return false;
	}		
	if($("input[name=logType]:checked").length == 0){		
		$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b> Please Select Employer Type</span>");
		return false;
	}	  
	return true;
});
});
</script>	
</head>

<body>

<div class="header_top">
	<div class="main">
		<div class="top-banner">
			<img src="images/acro-logo.jpg" class="acro_logo" />
			<img src="images/timeport-logo.png" width="160" class="timeport_logo" />
			<div class="clear"></div>
		</div>
	</div>
</div>

<div class="menu">
	<div class="main">
		<span class="login_page_heading" style="padding: 11px 0 0 0;display: block;">Acro TimePort</span>
	</div>
</div>


	
<div class="login_img" id="finalid">
	
	<fieldset>
		
		<div id="ErrorMsg" style="margin: 5px auto;"></div>
		<form method="post" action="login_verification.php">
			
			<table cellpadding="5" cellspacing="0" border="0" class="login-form">
				<tr>
					<td>
						<span class="logtype_msg" style="display:none">Please Select Atleast One.</span>
						<span class="logtype_msg" style="display:none">Please Select Atleast One.</span>
						<?php if(isset($_COOKIE['logType']) && $_COOKIE['logType'] == "employee_login") { ?>
						<input type="radio" id="login_opt_id" name="logType" value= "employee_login" checked />
						<?php } else {?>
						<input type="radio" id="login_opt_id" name="logType" value= "employee_login" checked />
						<?php
						 }
						?>
								
						<label class="sub_lbl" for="login_opt_id"><span></span>Employee</label>
						<?php if(isset($_COOKIE['logType']) && $_COOKIE['logType'] == "acro_login") { ?>
						<input type="radio" id="login_opt_id2" name="logType" value= "acro_login" checked />
						<?php } else {?>
						<input type="radio" id="login_opt_id2" name="logType" value= "acro_login"/>
						<?php
						 }
						?>
						<label class="sub_lbl" for="login_opt_id2"><span></span>Acro Login</label>
						
					</td>
                                       
				</tr>
                                <tr>
                                        <td>
                                                <?php if(isset($_COOKIE['logType']) && $_COOKIE['logType'] == "clientmgr_login") { ?>
						<input type="radio" id="login_opt_id2" name="logType" value= "clientmgr_login" checked />
						<?php } else {?>
						<input type="radio" id="login_opt_id2" name="logType" value= "clientmgr_login"/>
						<?php
						 }
						?>
                                                <label class="sub_lbl" for="login_opt_id3"><span></span>Client Manager Login</label>
                                        </td>
                                
                                </tr>
				
				<tr>
					<td>
						<label class="sub_lbl">User Id</label>
						<div class="userid_icon"><input type="text" autocomplete="off" name="userId" id="userId" class="userid_crtl" value = "<?php echo $_COOKIE['userId']; ?>" /></div>
						<span class="userid_msg" style="display:none">UserName Required.</span>
					</td>
				</tr>
				
				<tr>
					<td>
						<label class="sub_lbl">Password</label>
						<div class="password_icon"><input type="password"  name="password" id="password" class="password_ctrl" /></div>
						<span class="password_msg" style="display:none">Password Required.</span>
					</td>
				</tr>
				
				<tr>
					<td colspan="2">
						<?php if(isset($_COOKIE['userId'])) { ?>
                                            <input type="checkbox" name="remember" id = "remember" checked="checked" value="1"> <label for="remember">Remember User Id</label>
											<input type="checkbox" name="remember" onclick="PRFEnableDisable();" id = "enabledisable" value="1"><label for="remember">enabledisable</label>
						<?php } else { ?>
                                                <input type="checkbox" name="remember" id = "remember" value="1"><label for="remember">Remember User Id</label>
												
												  
						<?php } ?>
					</td>
				</tr>
				
				<tr>
				  <input type="hidden" name="act" id="act" value="login" />	<div id="login_button">
					<td align="center"><input type="submit"  id="login_buttons" value="Login" class="btn_style" style="width: 98%;" /></td></div>
				</tr>
<tr><td></td></tr>
<tr>
				
					<td align="center"><a href="instructions.html" target="_blank" style="font-size:12px;">Click here for instructions</a></td></div>
				</tr>

				
				
			</table>
		</form>
		</fieldset>
	</div>
</body>
</html>
