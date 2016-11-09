<?php
session_start();
require_once("includes/headerEmployee.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Welcome to AcroTrac</title>
<link rel="stylesheet" href="template.css" type="text/css" />
<script type="text/javascript" src="js/jquery.min.js"></script>

<script type="text/javascript">


$(function(){
$("#submit_button").click(function() {
	var oldPassword = $("#oldPassword").val(); 
	var newPassword = $("#newPassword").val();
	dataString = 'oldPassword='+ oldPassword + '&newPassword='+newPassword;
	
	if($("#oldPassword").val() == ""){
		$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b> Type current password</span>");					
		$('#oldPassword').focus();
		return false;
	}
	
	if($("#newPassword").val() == ""){
		$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b> Type new password</span>");					
		$('#newPassword').focus();
		return false;
	}
	
	if($("#confirmPassword").val() == ""){
		$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b> Type confirm password</span>");					
		$('#confirmPassword').focus();
		return false;
	}

	if($("#confirmPassword").val() != $("#newPassword").val() ){
		$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b> The new password and conform password donot match</span>");					
		$('#confirmPassword').focus();
		return false;
	}
	
	
 if(oldPassword=='' || newPassword=='' ){
	alert("Please fill all fields");
	return false;
 }
 else{
	$.ajax({
	type: "POST",
	url: "change_password.php",
	data: dataString,
		success: function(data)
		{
		data =$.trim(data);		
		if(data == "SUCCESS") {			
		$("#ErrorMsg").html("<span class='success_msg'><b>Success :</b> Successfully password changed!</span>");								
		$("#oldPassword").val("");
		$("#newPassword").val("");
		$("#confirmPassword").val("");
		
		}
		else{
			$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b>"+data+"</span>");								
			return false;
		}
		
	}
	});
 }
	return false;
});


});
</script>

<script type="text/javascript">
$(document).ready(function()
{
	$("#oldPassword").focus();
	$("#cancel_button").click(function(){
	$("#oldPassword").val("");
	$("#newPassword").val("");
	$("#confirmPassword").val("");
	$("#oldPassword").focus();
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
		<div class="left">
		<?php 
		/***  Acro Login Label ***/
		if(trim($_SESSION['UserSessionObject']['logType']) == "acro_login"){
		?>
		<span class="username-lbl">
		<?php 
			echo $_SESSION['UserSessionObject']['Last_Name']." ";		
			echo $_SESSION['UserSessionObject']['First_Name'];		
		?>
		</span>
		<?php			 
		}	 
	    ?>
		
		
		<?php 
		/***  Employee Login Label ***/
		if(trim($_SESSION['UserSessionObject']['logType']) == "employee_login"){
		?>
		<span class="username-lbl">
		<?php 
			echo $_SESSION['UserSessionObject']['employee_lf_name'];		
		?>
		</span>		
		<span class="normal-lbl">
		<?php 
			echo $_SESSION['UserSessionObject']['client_name'];
		?>
		</span>
		
		<?php			 
		}	 
	    ?>
		</div>
			
			<div class="right">				
				<ul>
					<li>
						<a href="TimeExpenseSheet.php" class="back_btn"><span></span> Back</a>
					</li>
					<li>
						<a href="logout.php?func=logout" class="logout_btn"><span></span> Logout</a>
					</li>
					
				</ul>
			</div>
			
			<div class="clear"></div>
	</div>
</div>


<div class="main" id="container_style">
	<div class="text_container">
		
		<div id="ErrorMsg"></div>
		<form method="post" action="change-pwd.php">

			<div align="center"><img src="images\logged-as.png" />&nbsp;&nbsp;&nbsp; Logged in as <?php echo "<b>".$_SESSION['UserSessionObject']['UserName']."</b>";?></div>

			<table border="0" width="31%" ALIGN="center">
				
				<tr>
					<td>
						<label>Current Password</label>
						<div class="password_icon"><input type="password" autocomplete="off" name="oldPassword" id="oldPassword" class="password_ctrl" /></div>
						
					</td>
				</tr>
				
				<tr>
					<td>
						<label>New Password</label>
						<div class="password_icon"><input type="password" autocomplete="off" name="newPassword" id="newPassword" class="password_ctrl" /></div>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Confirm Password</label>
						<div class="password_icon"><input type="password" autocomplete="off" name="confirmPassword" id="confirmPassword" class="password_ctrl" /></div>						
					</td>
				</tr>				
				<tr>
				  
					<td align="center"><input type="button"  id="submit_button" value="Change Password" class="btn_style" style="width: 63%;" />
					<input type="button"  id="cancel_button" value="Cancel" class="btn_style" style="width: 35%;" /></td>
				</tr>	
				
			</table>
		</form>
		</div>
	
</div>
</body>
</html>