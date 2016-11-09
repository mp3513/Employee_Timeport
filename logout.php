<?php
session_start();
if(isset($_GET['func']) && !empty($_GET['func'])){
	unset($_SESSION);
	session_destroy();
	header("location:index.php");
}
else{
	header("location:index.php");
}
?>
