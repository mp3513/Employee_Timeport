<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Acro TimePort</title>
<link rel="stylesheet" href="template.css" type="text/css" />
</head>
<body>
<div class="header_top">
	<div class="main">
		<img src="images/acro-logo.jpg" />
	</div>
</div>

<div class="menu"></div>

<div class="main" id="login_container_style">
	<div class="text_container ">
		<div class="login_img" style="text-align: center; font-weight: bold; height: 207px; color:#4B4B4B;">
				
<?php
require_once("includes/db_connection_irm.php");
require_once("includes/functions.php");
session_start();
$iconn= getIRMDatabaseConnection();
$act = trim($_POST['act']);


if($_POST['userId'] == "" || $_POST['password'] == "" || $_POST['logType'] == "" || $_POST['act'] == ""){
	print "<br>Sorry! Please Provide Full Details.";
    print "<br><a href='index.php'>Go Back</a>";
	exit;
}

if(isset($_POST['remember'])){           
            setcookie('userId', $_POST['userId'] , time() + 1*24*60*60);
			setcookie('logType', $_POST['logType'] , time() + 1*24*60*60); 			
}else{            
            setcookie('userId', '', time() - 1*24*60*60);           
			setcookie('logType', '', time() - 1*24*60*60);
}
		
$logData = array(   
                    "userid"=>trim($_POST['userId']),
                    "password"=>$_POST['password'],
                    "logType"=>trim($_POST['logType'])
				);

if(isset($act) && !empty($act) && $act=='login'){
	if($logData['logType'] == "acro_login"){
		 loginAuthenticationAcroUser($iconn,"AppUser",$logData);
	}
	else if($logData['logType'] == "employee_login"){
		 loginAuthenticationEmployee($iconn,"EmployeeTimePortCredentials",$logData);
	}
        else if($logData['logType'] == "clientmgr_login"){
		 loginAuthenticationClientMgr($iconn,"tblCustomerManager",$logData);
	}
		
}


function loginAuthenticationAcroUser($iconn,$table,$logData) {

	 $SQL ="SELECT row_id,user_id,pwd,first_name,last_name,active,PayrollAdminRoleId FROM ".$table." WHERE user_id ='$logData[userid]' AND pwd COLLATE Latin1_General_CS_AS= '$logData[password]'";
	$result = sqlsrv_query($iconn, $SQL , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) ;						
	$countRow = sqlsrv_num_rows($result);
      
	if($countRow==1){
					$data = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
					if((strcasecmp(trim($data['user_id']), $logData['userid']) == 0 ) && trim($data['pwd']) == $logData['password'] )
					{				
						$_SESSION['UserSessionObject']['UserID'] 			= trim($data['user_id']);
						$_SESSION['UserSessionObject']['row_id'] 			= $data['row_id'];
						$_SESSION['UserSessionObject']['First_Name'] 		= trim($data['first_name']);
						$_SESSION['UserSessionObject']['Last_Name'] 		= trim($data['last_name']);
						$_SESSION['UserSessionObject']['Active'] 			= $data['active'];
						$_SESSION['UserSessionObject']['RoleID'] 			= $data['PayrollAdminRoleId'];								
						$_SESSION['UserSessionObject']['logType'] 			= $logData['logType'];
						$_SESSION['UserSessionObject']['customer_available'] =0;						
						getCustomerAvailable($iconn,$_SESSION['UserSessionObject']['row_id'],$_SESSION['UserSessionObject']['RoleID']);											
						sqlsrv_free_stmt($result);
						//sqlsrv_close($iconn);						
						header("Location:ViewTimeExpenseHistory.php?func=search");
					}else{
						echo "<br>Sorry UserId or Password Incorrect!";
						echo "<br><a href='index.php'>Click to try again </a>";
						}
	}
	else{
			echo "<br>Sorry UserId or Password Incorrect!";
			echo "<br><a href='index.php'>Click to try again </a>";
			//sqlsrv_close($iconn);
	}
}
/**********     Function for login authentication(Client Manager)     *****/
function loginAuthenticationClientMgr($iconn,$table,$logData)
{
    
    $sqlclientcredentials = "select customer.customer_id,Username,tblCustomerManager.Password,client_fk , CustMgr_id ,FirstName,LastName,IsActive,NumberofLoginAttempt,client_fk
                            from tblCustomerManager left join customer  on customer.customer_id = tblCustomerManager.client_fk 
                            where Username = '$logData[userid]' and inactive = 'N' and TimePortClientApprover = '1'  and IsActive = '1' and TimeportClientApprovalFlag = '1' and NumberOfLoginAttempt <= 6 and pw_expiry_date > GETDATE() and IsAccountLocked = '0'";
    
    $result = sqlsrv_query($iconn, $sqlclientcredentials , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));						
    $countRow = sqlsrv_num_rows($result);

    if($countRow == 1)
    {               
        $dataClientMgr = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC); 
       
        if((strcasecmp(trim($dataClientMgr['Username']), $logData['userid']) == 0 ) && trim($dataClientMgr['Password']) == $logData['password'] )
        {
           
            $_SESSION['ClientSessionObject']['username']                = $dataClientMgr['Username'];
            $_SESSION['ClientSessionObject']['row_id']                  = $dataClientMgr['CustMgr_id'];
            $_SESSION['ClientSessionObject']['First_Name'] 		= $dataClientMgr['FirstName'];
            $_SESSION['ClientSessionObject']['Last_Name'] 		= $dataClientMgr['LastName'];
            $_SESSION['ClientSessionObject']['client']                  = $dataClientMgr['client_fk'];
            $_SESSION['ClientSessionObject']['Active'] 			= $dataClientMgr['IsActive'];       							
            $_SESSION['ClientSessionObject']['logType'] 		= $logData['logType'];
           $sqlupdate = "Update tblCustomerManager set LastLoginDate = GETDATE(),UserIP = '' where CustMgr_id ='".$dataClientMgr['CustMgr_id']."'";
           $resultupdate = sqlsrv_query($iconn, $sqlupdate , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
            
           header("Location:ClientManagerview.php?func=search");
        }
        else
        {  
            //echo $dataClientMgr['NumberofLoginAttempt']; echo "<br>";  
            $count = ($dataClientMgr['NumberofLoginAttempt'] + 1);
            //echo $count; echo "<br>";         
            $sqlupdate = "Update tblCustomerManager set NumberofLoginAttempt = NumberofLoginAttempt + 1 where CustMgr_id ='".$dataClientMgr['CustMgr_id']."'";
            $resultupdate = sqlsrv_query($iconn, $sqlupdate , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
            $dataupdate = sqlsrv_fetch_array( $resultupdate, SQLSRV_FETCH_ASSOC);
            if($count > 6)
            {
                $sqlup = "Update tblCustomerManager set IsAccountLocked = '1' where CustMgr_id ='".$dataClientMgr['CustMgr_id']."'";
                $resultup= sqlsrv_query($iconn, $sqlup , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
                echo "<div align='center'>Unauthorised Use ! This Login has been Locked </div>";
                echo "<div align='center'>Please contact Acro System Administrator</div>";
           }
            else
            {
                $attempt = 7 - $count;
                //echo $attempt;
                 echo "<br>Login Failed!";
                 echo "<br>You have ".$attempt." remaining attempts.";
                 echo "<br><a href='index.php'>Click to try again </a>";
            }
        }
    }
    else
    {
        echo "<br>Sorry UserId or Password Incorrect!";
	echo "<br><a href='index.php'>Click to try again </a>";
    }

}

/**********     Function for login authentication(employee)     *****/
function loginAuthenticationEmployee($iconn,$table,$logData){	
	
	$sqlTimePortCredentials = "SELECT e.employee_id,e.UserName,e.Password,c.email FROM EmployeeTimePortCredentials as e,Candidate as c 
								WHERE e.UserName ='$logData[userid]' 
								AND e.Password COLLATE Latin1_General_CS_AS = '$logData[password]' 
								AND e.employee_id = c.row_id";

						// AND c.cand_placement_status = 'H' : HKS 8Jan 14  code removed
		$result = sqlsrv_query($iconn, $sqlTimePortCredentials , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) or die(print_r(sqlsrv_errors()));						
		$countRow = sqlsrv_num_rows($result);
                
               
			
									
		if($countRow==1){
					$dataEmployee = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC); 
																									
					if((strcasecmp(trim($dataEmployee['UserName']), $logData['userid']) == 0 ) && trim($dataEmployee['Password']) == $logData['password'] )
					{
						$employee_id = trim($dataEmployee['employee_id']);
						$sqlEmpPlacement = "SELECT * FROM EmployeePlacement WHERE employee_id='$employee_id' AND termination_date IS NUll";					
						$resultEmpPlacement = sqlsrv_query($iconn, $sqlEmpPlacement , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));							
						$totalEmployeePlacementRecord =	sqlsrv_num_rows($resultEmpPlacement);

						
						if($totalEmployeePlacementRecord >=1){
							$dataEmpPlacement = sqlsrv_fetch_array( $resultEmpPlacement, SQLSRV_FETCH_ASSOC);
							
							$firstName = id_string($iconn,"candidate", $dataEmpPlacement['employee_id'], "row_id", "first_name");	
							$lastName  = id_string($iconn,"candidate", $dataEmpPlacement['employee_id'], "row_id", "last_name");						
						   
							$_SESSION['UserSessionObject']['employee_id'] 			= trim($dataEmployee['employee_id']);
							$_SESSION['UserSessionObject']['UserName'] 				= trim($dataEmployee['UserName']);											
							$_SESSION['UserSessionObject']['employee_lf_name']		= $lastName." ".$firstName;
							$_SESSION['UserSessionObject']['client_fk']  			= $dataEmpPlacement['client_fk'];
							$_SESSION['UserSessionObject']['logType'] 			    = $logData['logType'];							
							$_SESSION['UserSessionObject']['client_name'] 			= id_string($iconn,"customer", $dataEmpPlacement['client_fk'], "customer_id", "customer_name");	
							$_SESSION['UserSessionObject']['manual_timesheet'] 		= id_string($iconn,"customer", $dataEmpPlacement['client_fk'], "customer_id", "Isempmanualtimesheet"); 
							$_SESSION['UserSessionObject']['Time_entryStyle'] 		= id_string($iconn,"customer", $dataEmpPlacement['client_fk'], "customer_id", "TimePortEntryStyle");						
							$_SESSION['UserSessionObject']['acroPAdminId'] 			= id_string($iconn,"customer", $dataEmpPlacement['client_fk'], "customer_id", "PayrollAdministrator"); 
							$_SESSION['UserSessionObject']['empPlacementId'] 		= $dataEmpPlacement['row_id'];
							$_SESSION['UserSessionObject']['candidate_email'] 		= $dataEmployee['email']; // change hks for mail
                                                        $_SESSION['UserSessionObject']['client_mgr_firstname']                    = id_string_client($iconn,$dataEmployee['employee_id'],"firstname");
                                                        $_SESSION['UserSessionObject']['client_mgr_lastname']                    = id_string_client($iconn,$dataEmployee['employee_id'],"lastname");
                                                        $_SESSION['UserSessionObject']['altclient_mgr_firstname']                    = id_string_altclient($iconn,$dataEmployee['employee_id'],"firstname");
                                                        $_SESSION['UserSessionObject']['altclient_mgr_firstname']                    = id_string_altclient($iconn,$dataEmployee['employee_id'],"lastname");
							sqlsrv_free_stmt($result);
							sqlsrv_free_stmt($resultEmpPlacement);
							header("Location:TimeExpenseSheet.php");
						}
						else if($totalEmployeePlacementRecord ==0)
							{
								$currentDate = date("Y-m-d");
								$tillLoginDate	= "";
								$allowLogin		= 0;
								
								$sqlEmpPlacementWithTerminated = "SELECT TOP 1 * FROM EmployeePlacement WHERE employee_id='$employee_id' AND termination_date IS NOT NUll ORDER BY termination_date DESC ";									
								$resultEmpPlacementTerminated = sqlsrv_query($iconn, $sqlEmpPlacementWithTerminated , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));							
								$totalEmployeePlacementTerminatedRecord =	sqlsrv_num_rows($resultEmpPlacementTerminated);
								
								if($totalEmployeePlacementTerminatedRecord ==1)
								{
									
									$dataEmpPlacementTerm = sqlsrv_fetch_array($resultEmpPlacementTerminated, SQLSRV_FETCH_ASSOC);																					
									$termination_date = date_format($dataEmpPlacementTerm['termination_date'], 'Y-m-d');
									if(strtotime($currentDate) <= strtotime($termination_date)){
										$allowLogin	= 1;
									}else if(strtotime($currentDate) > strtotime($termination_date)){
											// $day = date('D', strtotime($termination_date));
											$tillLoginDate = addDays($termination_date);
											if(strtotime($currentDate) > strtotime($tillLoginDate)){
												$allowLogin	= 0;
											}
											else{
												$allowLogin	= 1;											
											}
									}
								
								}else{								
									echo "<br>Sorry, No record fount for you.<br><a href='index.php'>Click to try again </a>";								
								}
								
								if($allowLogin	== 0 ){	
									echo "Sorry, but you do not currently have permission to Login.";
									echo "<br><a href='index.php'>Go to Login Page</a>";							
								}else{
									$firstName = id_string($iconn,"candidate", $dataEmpPlacementTerm['employee_id'], "row_id", "first_name");	
									$lastName  = id_string($iconn,"candidate", $dataEmpPlacementTerm['employee_id'], "row_id", "last_name");						

									$_SESSION['UserSessionObject']['employee_id'] 			= trim($dataEmployee['employee_id']);
									$_SESSION['UserSessionObject']['UserName'] 				= trim($dataEmployee['UserName']);											
									$_SESSION['UserSessionObject']['employee_lf_name']		= $lastName." ".$firstName;
									$_SESSION['UserSessionObject']['client_fk']  			= $dataEmpPlacementTerm['client_fk'];
									$_SESSION['UserSessionObject']['logType'] 			    = $logData['logType'];
									$_SESSION['UserSessionObject']['client_name'] 			= id_string($iconn,"customer", $dataEmpPlacementTerm['client_fk'], "customer_id", "customer_name");	
								 echo 	$_SESSION['UserSessionObject']['Manual_Timesheet'] 		= id_string($iconn,"customer", $dataEmpPlacementTerm['ISEMPMANUALTIMESHEET'], "customer_id", "customer_name");	 
									$_SESSION['UserSessionObject']['acroPAdminId'] 			= id_string($iconn,"customer", $dataEmpPlacementTerm['client_fk'], "customer_id", "PayrollAdministrator");
									$_SESSION['UserSessionObject']['empPlacementId'] 		= $dataEmpPlacementTerm['row_id'];
									$_SESSION['UserSessionObject']['candidate_email'] 		= $dataEmployee['email']; // change hks for mail
									
									sqlsrv_free_stmt($result);
									sqlsrv_free_stmt($resultEmpPlacementTerminated);
									header("Location:TimeExpenseSheet.php");	
								}
								
								sqlsrv_free_stmt($result);
								sqlsrv_free_stmt($resultEmpPlacementTerminated);								
							}
					
					}else{
						echo "<br>Sorry UserId or Password Incorrect!";
						echo "<br><a href='index.php'>Click to try again </a>";
					}					
	}
	else{					
			echo "<br>Sorry UserId or Password Incorrect!";
			echo "<br><a href='index.php'>Click to try again </a>";
			//sqlsrv_close($iconn);
	}	
	
}	// end of the function




/******************** function for adding date ***************/

function addDays($termination_date)
{
$termination_date= date('Y-m-d',strtotime($termination_date . "+30 days"));
return $termination_date;


/*
// 30th Jan 2014 : 30 days more login process

$day = date('D', strtotime($termination_date));
	$tillLoginDate="";
	switch($day)
	{
		case "Mon":
		$tillLoginDate= date('Y-m-d',strtotime($termination_date . "+13 days"));
		break;

		case "Tue":
		$tillLoginDate= date('Y-m-d',strtotime($termination_date . "+12 days"));
		break;

		 case "Wed":
		 $tillLoginDate= date('Y-m-d',strtotime($termination_date . "+11 days"));
		 break;

		case "Thu":
		$tillLoginDate= date('Y-m-d',strtotime($termination_date . "+10 days"));
		break;

		case "Fri":
		$tillLoginDate= date('Y-m-d',strtotime($termination_date . "+9 days"));
		break;

		case "Sat":
		$tillLoginDate= date('Y-m-d',strtotime($termination_date . "+8 days"));
		break;
		
		case "Sun":
		$tillLoginDate= date('Y-m-d',strtotime($termination_date . "+7 days")); // +
		break;
											
	}
	return $tillLoginDate;
*/	
}



function getCustomerAvailable($iconn,$row_id,$roleId){
	
	
	if($roleId != 1){ 
		 $customerQuery = "SELECT customer_id FROM customer WHERE ManualTimesheets=1 "; 
	}else{
	 	$customerQuery = "SELECT customer_id FROM customer WHERE PayrollAdministrator ='$row_id' and ManualTimesheets=1 "; 
	}
	
	$resultCustomer = sqlsrv_query($iconn, $customerQuery , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) or die("Search Data From Customer");			
	$totalRecordCustomer =	sqlsrv_num_rows($resultCustomer);
	
	if($totalRecordCustomer!=0)
	{
		 $_SESSION['UserSessionObject']['customer_available']  =1; 
		
		///////////////   Old Code with employeePlacement : HKS /////////////////////////
		// while($data = sqlsrv_fetch_array( $resultCustomer, SQLSRV_FETCH_ASSOC))
		// {
		// $customerIdList = $customerIdList.$data['customer_id'].",";
		// }
		// $customerIdList = rtrim($customerIdList, ",");

		// sqlsrv_free_stmt($resultCustomer);
		// if($customerIdList!=""){
		// $employeePlacementQuery  =  "SELECT row_id FROM EmployeePlacement WHERE client_fk IN($customerIdList) AND termination_date IS NULL and isTermStatusViewedByAcroPayroll =0 ";
		// $resultEmployee = sqlsrv_query($iconn, $employeePlacementQuery , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) or die("Error Search Data From EmployeePlacement");			
		// $totalRecordEmployee =	sqlsrv_num_rows($resultEmployee);	
		// if($totalRecordEmployee!=0){
		// $_SESSION['UserSessionObject']['employee_available']  =1;							
		// }
		// }
							
	}
} // end of function	
	
function countSundays($iconn,$startDate,$date){
	$SQL= "Select DateDiff(ww, '$startDate', '$date') as NumOfSundays";
	$resultSet = sqlsrv_query($iconn, $SQL , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) ;						
	$row = sqlsrv_fetch_array($resultSet, SQLSRV_FETCH_ASSOC);
	sqlsrv_free_stmt($resultSet);
	return $row['NumOfSundays'];
}
?>

</div>
</div>
</div>
</body>
</html>