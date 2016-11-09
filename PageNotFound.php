<?php 
session_start();



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Acro TimePort</title>  

<script type="text/javascript" src="js/jquery.min.js"></script>



<style>

.tbl th{width:9%;}



</style


</head>
<body id="clickedone" >


		
<div class="main" id="container_style">		
		<div align="center">
			<div id="SuccessMsg">
			
			</div>
			
			<div id="ErrorMsg"></div>
		</div>
		
		
		
		<div style="height:350px;margin-top: 151px;">		
		<span style="margin-left: 370px; font-size: 22px;">Sorry! File not available</span></br></br></br>
		<a style="margin-left: 444px; color:#FB220F;" href="javascript:window.close();">Close</a>
		
</div>	
</div>
</body>
</html>
<?php

 sqlsrv_close($iconn);

 ?>
