<?php
session_start();
require_once("includes/db_connection_irm.php");
require_once("includes/functions.php");
$iconn 		 = getIRMDatabaseConnection();
$userId		 = $_SESSION['UserSessionObject']['UserName'];
$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];
$msg = 0;
if($oldPassword =="" || $newPassword == "" || $userId == ""){
	$msg = 0;
}
else{
	$SQL = "SELECT * FROM EmployeeTimePortCredentials WHERE UserName='$userId' AND Password COLLATE Latin1_General_CS_AS='$oldPassword'";
	$result = sqlsrv_query($iconn, $SQL , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) or die("ERROR Query");			
	$nr =	sqlsrv_num_rows($result);
	if($nr == 1){
		$updateSQL = "UPDATE EmployeeTimePortCredentials SET Password ='$newPassword' WHERE UserName = '$userId'";
		$rs = sqlsrv_query($iconn, $updateSQL , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) or die("Password Change Error");
		$msg = 1;
	}
	else{
		$msg = 2;
	}
}
sqlsrv_free_stmt($result);
sqlsrv_close($iconn);
if($msg ==0){
	echo "Less Data Found";
}
else if($msg == 1){
	echo "SUCCESS";
}
else if($msg ==2){
	echo "Old password donot match";
}
?>