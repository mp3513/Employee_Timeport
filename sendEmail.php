<?php

$sheetType= "TimeSheet";
$to = "hrathore@acrocorp.com";
$from= "acrotech.sys@gmail.com";
$subject= $sheetType.""."Status";
$mailBody=  "This is a system generated message  "."<br><br>";
$mailBody.= "Your $sheetType has been submitted:"."<br>";

if(mail($from,$to,$subject,$mailBody)){
echo "Send Email:::::::::::::";

}else{
echo "Email not sent:::::::::::::";}
?>