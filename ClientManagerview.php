<?php 

session_start();
require_once("includes/db_connection_irm.php");
require_once("includes/functions.php");
require_once("ConfigurationRead.php");
require_once("includes/headerClient.php");


$iconn 		 = getIRMDatabaseConnection();
$uploadPath  = getKeyValue("UploadPath","upload.config");
$clientmgrid = $_SESSION['ClientSessionObject']['row_id'];
$client = $_SESSION['ClientSessionObject']['client'];


if($_GET['func'] == "search")
    {	
				
	$_SESSION['status'] = "S";
                            
    }
    
if($_REQUEST['weekEndingDate']!="")
			{
				$getWeekDate=$_REQUEST['weekEndingDate'];
				//$_SESSION[weekEndingDate]=$_REQUEST['weekEndingDate'];

			}
else if($_SESSION[weekEndingDate]!="")
			{
				$weekEndingDate	= $_SESSION[weekEndingDate];
				$_SESSION['weekEndingDate'] = $weekEndingDate;	
				$Displaydate=date('m/d/Y',strtotime($_SESSION['weekEndingDate']));
			}


if($_REQUEST['weekEndingDate'] =="" && $_REQUEST['status']=="S")
		{  
			$_SESSION[weekEndingDate] ="";
			$Displaydate="";
		}

else if($getWeekDate!="")
		{
			//$_SESSION['weekEndingDate'] = trim($getWeekDate);
			$weekEndingDate	= convertDatetoYYDDMMFormat(trim($getWeekDate));	
			$_SESSION['weekEndingDate'] = $weekEndingDate;	
			$Displaydate=date('m/d/Y',strtotime($getWeekDate));
		} 
                
if(isset($_REQUEST['status']) && !empty($_REQUEST['status']))
    {
	$_SESSION['status'] = trim($_REQUEST[status]);
    }
                 
if((isset($_REQUEST['empstatus']) && !empty($_REQUEST['empstatus'])))
		{
			$_SESSION['empstatus'] = trim($_REQUEST[empstatus]);	 
	
		}
if((isset($_REQUEST['empstatus']) && !empty($_REQUEST['empstatus']) && $_REQUEST['empstatus']=="AL"))
		{
		 $_SESSION['empstatus'] = trim($_REQUEST[empstatus]);
		
		}               


$sqlclient ="select distinct customer_name from customer inner join tblCustomerManager on customer.customer_id = tblCustomerManager.client_fk where client_fk = '".$client."'";
$resultclient = sqlsrv_query($iconn, $sqlclient , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
$dataClient = sqlsrv_fetch_array( $resultclient, SQLSRV_FETCH_ASSOC); 


$Select = "select costsheet.employee_fk,first_name,last_name,tblAcroPortRecords.weekendingdate as wedte ,tblAcroPortRecords.submittedon as sub,tblAcroPortRecords.rowID as rid,EmpPlacementId as empplaceid,TimePortEntrystyle,client_site,PrimaryClientManager_FK,termination_date,CASE status WHEN 'S' THEN 'Submitted' WHEN 'I' THEN 'Ignored' 
	 WHEN 'V' THEN 'Viewed By Acro'  WHEN 'A' THEN 'Approved' WHEN 'R' THEN 'Declined'  END as status,placementid_fk,CASE WHEN Candidate.cand_placement_status ='H' then 'Active' else 'Terminated' END as [e.empstatus],timeSheetDocName,expenseSheetDocName
                  from tblCustomerManager 
                  left join costsheet on (tblCustomerManager.CustMgr_id = costsheet.PrimaryClientManager_FK or tblCustomerManager.CustMgr_id = costsheet.AlternateClientManager_FK )
                  inner join employeeplacement on employeeplacement.row_id = costsheet.placementid_fk 
                  inner join tblAcroPortRecords on employeeplacement.row_id = tblAcroPortRecords.empPlacementID 
                  full outer  join Candidate on costsheet.employee_fk = Candidate.row_id"; 

$SelectNotSelect = "SELECT distinct ep.row_id,ep.termination_date,ep.isTermStatusViewedByAcroPayroll,'' as expenseSheetDocName, 
	 ct.first_name, ct.last_name,ct.ssn as 'SSNo',cu.customer_name,cu.AccCustomerID as ClientId,CASE WHEN isnull(ep.termination_date,'')='' then 'Active' else 'Terminated' END as [e.empstatus]";

$sqlclient = "and CustMgr_id = '$clientmgrid'";
                 
if($_SESSION['status']=="S")
{
    if($_SESSION['weekEndingDate'] == "")
    {
        if($_SESSION['empstatus']=="H")
        {            
            $SQLSelect = $Select." where tblAcroPortRecords.status = 'S' and Candidate.cand_placement_status ='H' ".$sqlclient;            
        }
        else if($_SESSION['empstatus']=="T")
        {
             $SQLSelect = $Select." where tblAcroPortRecords.status = 'S' and Candidate.cand_placement_status ='T' ".$sqlclient;
        }
         else 
        {
            $SQLSelect = $Select." where tblAcroPortRecords.status = 'S' ".$sqlclient;
        }
               
           
    }
    else
    {
        if($_SESSION['empstatus']=="H")
        {            
            $SQLSelect = $Select." where status = 'S' and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]' and Candidate.cand_placement_status ='H' ".$sqlclient;            
        }
        else if($_SESSION['empstatus']=="T")
        {
             $SQLSelect = $Select." where status = 'S' and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]'  and Candidate.cand_placement_status ='T' ".$sqlclient;
        }
         else 
        {
            $SQLSelect = $Select." where status = 'S' and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]' ".$sqlclient;
        }
               
    }
   
}

else if($_SESSION['status']=="A")
{
    if($_SESSION['weekEndingDate'] == "")
    {
        if($_SESSION['empstatus']=="H")
        {
             $SQLSelect = $Select." where status = 'A' and Candidate.cand_placement_status ='H' ".$sqlclient; 
        }
        else if($_SESSION['empstatus']=="T")
        {
             $SQLSelect = $Select." where status = 'A' and Candidate.cand_placement_status ='T' ".$sqlclient;
        }
         else 
        {
            $SQLSelect = $Select." where status = 'A' ".$sqlclient;
        }
    }
    else
    {
        if($_SESSION['empstatus']=="H")
        {            
            $SQLSelect = $Select." where status = 'A' and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]'  and Candidate.cand_placement_status ='H' ".$sqlclient;            
        }
        else if($_SESSION['empstatus']=="T")
        {
             $SQLSelect = $Select." where status = 'A' and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]'  and Candidate.cand_placement_status ='T' ".$sqlclient;
        }
         else 
        {
            $SQLSelect = $Select." where status = 'A' and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]' ".$sqlclient;
        }
               
    }
}

else if($_SESSION['status']=="R")
{
    if($_SESSION['weekEndingDate'] == "")
    {
        if($_SESSION['empstatus']=="H")
        {
             $SQLSelect = $Select." where status = 'R' and Candidate.cand_placement_status ='H' ".$sqlclient; 
        }
        else if($_SESSION['empstatus']=="T")
        {
             $SQLSelect = $Select." where status = 'R' and Candidate.cand_placement_status ='T' ".$sqlclient;
        }
         else 
        {
            $SQLSelect = $Select." where status = 'R' ".$sqlclient;
        }
    }
    else
    {
        if($_SESSION['empstatus']=="H")
        {            
            $SQLSelect = $Select." where status = 'R' and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]'  and Candidate.cand_placement_status ='H' ".$sqlclient;            
        }
        else if($_SESSION['empstatus']=="T")
        {
             $SQLSelect = $Select." where status = 'R' and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]'  and Candidate.cand_placement_status ='T' ".$sqlclient;
        }
         else 
        {
            $SQLSelect = $Select." where status = 'R' and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]' ".$sqlclient;
        }
               
    }
}
else if($_SESSION['status']=="V")
{
    if($_SESSION['weekEndingDate'] == "")
    {
        if($_SESSION['empstatus']=="H")
        {
             $SQLSelect = $Select." where (status = 'V' or status = 'I') and Candidate.cand_placement_status ='H' ".$sqlclient; 
        }
        else if($_SESSION['empstatus']=="T")
        {
             $SQLSelect = $Select." where (status = 'V' or status = 'I') and Candidate.cand_placement_status ='T' ".$sqlclient;
        }
         else 
        {
            $SQLSelect = $Select." where (status = 'V' or status = 'I') ".$sqlclient;
        }
    }
    else
    {
        if($_SESSION['empstatus']=="H")
        {            
            $SQLSelect = $Select." where (status = 'V'  or status = 'I') and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]'  and Candidate.cand_placement_status ='H' ".$sqlclient;            
        }
        else if($_SESSION['empstatus']=="T")
        {
             $SQLSelect = $Select." where (status = 'V' or status = 'I') and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]'  and Candidate.cand_placement_status ='T' ".$sqlclient;
        }
         else 
        {
            $SQLSelect = $Select." where (status = 'V' or status = 'I') and tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]' ".$sqlclient;
        }
               
    }
}

else if($_SESSION['status']=="N")
    { 
		
    $weekEndingDate  = $_SESSION[weekEndingDate];
    $weekStartDate=date('Y/m/d', strtotime('-6 days', strtotime($weekEndingDate)));
	
    if($_SESSION['empstatus']=='H')
	{ 								
            $SQLSelect=$SelectNotSelect." from tblCustomerManager pa
                              join customer cu on cu.customer_id =pa.client_fk and cu.ManualTimesheets=1 
                              join employeePlacement ep on ep.client_fk = cu.customer_id
                              join costsheet co on co.Client_site = ep.client_fk
                              join candidate ct on ep.employee_id = ct.row_id and	ct.cand_placement_status='H'
                              where ep.row_id not in (select empPlacementId from tblAcroPortRecords where weekEndingDate='$weekEndingDate' ) and 
                           (ep.termination_date is null or ep.termination_date >= '$weekStartDate') 
                          and (ep.start_date <= '$weekEndingDate' ) and cu.Isempmanualtimesheet=1 and (PrimaryClientManager_FK = '1' or AlternateClientManager_FK = '1')";
	}
		
		
    else  if($_SESSION['empstatus']=='T')
				 {				
				
				$SQLSelect=$SelectNotSelect." from tblCustomerManager pa
                                join customer cu on cu.customer_id =pa.client_fk and cu.ManualTimesheets=1 
                                join employeePlacement ep on ep.client_fk = cu.customer_id
                                join costsheet co on co.Client_site = ep.client_fk
                                join candidate ct on ep.employee_id = ct.row_id and	ct.cand_placement_status='T'  
                                where ep.row_id not in
				(select empPlacementId from tblAcroPortRecords where weekEndingDate='$weekEndingDate' ) and
				(ep.termination_date is null or ep.termination_date >= '$weekStartDate')
				and (ep.start_date <= '$weekEndingDate' ) and cu.Isempmanualtimesheet=1";
								
				}	
				
				else {
	
							
				$SQLSelect=$SelectNotSelect." from tblCustomerManager pa
                              join customer cu on cu.customer_id =pa.client_fk and cu.ManualTimesheets=1 
                              join employeePlacement ep on ep.client_fk = cu.customer_id
                              join costsheet co on co.Client_site = ep.client_fk 
                                join candidate ct on ep.employee_id = ct.row_id   
                                where ep.row_id not in
				(select empPlacementId from tblAcroPortRecords where weekEndingDate='$weekEndingDate' ) and
				(ep.termination_date is null or ep.termination_date >= '$weekStartDate')
				and (ep.start_date <= '$weekEndingDate' ) and cu.Isempmanualtimesheet=1";
												
				
				}				
				
    }

else
{
    if(isset($_REQUEST['weekEndingDate']) && !empty($_REQUEST['weekEndingDate']))
    {
    if($_SESSION['empstatus']=="H")
        {            
            $SQLSelect = $Select." where tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]'  and Candidate.cand_placement_status ='H' ".$sqlclient;            
        }
        else if($_SESSION['empstatus']=="T")
        {
             $SQLSelect = $Select." where tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]'  and Candidate.cand_placement_status ='T' ".$sqlclient;
        }
         else 
        {
            $SQLSelect = $Select." where tblAcroPortRecords.weekendingdate = '$_SESSION[weekEndingDate]' ".$sqlclient;
        }
}
}
//echo $SQLSelect;
$result = sqlsrv_query($iconn, $SQLSelect , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) ;						
$nr = sqlsrv_num_rows($result);
 
            ///////pagination
       /* if (isset($_GET['pn'])) { 
 	$pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); 
	} else { 
	$pn = 1;
	}
	$itemsPerPage = 2; 
	$lastPage = ceil($nr / $itemsPerPage);

	if ($pn < 1) { 
    $pn = 1; 
	} else if ($pn > $lastPage) { 
    $pn = $lastPage; 
	} 
		$centerPages = "";
		$sub1 = $pn - 1;
		$sub2 = $pn - 5;
		$add1 = $pn + 1;	
		$add2 = $pn + 5;
		if ($pn == 1) {
			$centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
			$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
			
		} else if ($pn == $lastPage) {
			$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
			$centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
		} else if ($pn > 5 && $pn < ($lastPage - 1)) {
			$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub2 . '">' . $sub2 . '</a> &nbsp;';
			$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
			$centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
			$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
			$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add2 . '">' . $add2 . '</a> &nbsp;';
		} else if ($pn > 1 && $pn < $lastPage) {
			$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
			$centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
			$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
		}

		$paginationDisplay = ""; 

		if ($lastPage != "1"){    
			$paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage. '&nbsp;  &nbsp;  &nbsp; ';
			
			if ($pn != 1) {
				$previous = $pn - 1;
				$paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $previous . '"> Back</a> ';
			}     
			$paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
			
			if ($pn != $lastPage) {
				$nextPage = $pn + 1;
				$paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $nextPage . '"> Next</a> ';
			} 
		}           
           */ 
           
            

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Acro TimePort</title>
    
<link rel="stylesheet" href="template.css" type="text/css" />
<link rel="stylesheet" href="datepicker/jquery.ui.all.css">
<script src="datepicker/jquery-1.4.4.js"></script>      
<script src="datepicker/jquery.ui.core.js"></script>
<script src="datepicker/jquery.ui.widget.js"></script>
<script src="datepicker/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="datepicker/demos.css">          
<script src="js/validationclient.js"></script>
<script>
function countChar(val) { 
        var len = val.value.length;
		
        if (len >= 200) {
          val.value = val.value.substring(0, 200);
		  $('#charNum').text('0/200 characters remaining');
        } else {
          $('#charNum').text(200 - len +'/200 characters remaining');
        }
      };
      
function startDownload(folderName,fileName)  
{
	  var sitename ="http://localhost/timeport1";
	  // var sitename ="http://10.0.0.125/timeport";
	  var url=sitename+"/"+folderName+ "/"+fileName;
    window.open(url, "", "width=1000, height=600"); 
}  



</script>

<script>
$(function(){
        $("#startDateSearch").datepicker(
            {
            beforeShowDay: function (date) {
            if (date.getDay() == 0) {
                return [true, ''];
            }
            return [false, ''];
           }
        });
    });
   

</script>
<script>
function GetTimeSheetOnClick(Weekdate,Empid)
{		
       var empPlacementId = Empid;
	   var str=Weekdate;
	  dataString= 'weekendingdate='+str + '&empPlacementId='+empPlacementId;
	  
    $.ajax({ 
		type: "POST",
		url: "EnterTimePopupClient.php",
		data: dataString,		
		success: function(msg){	
	       msg = $.trim(msg);
		   
if(msg!="")
{		
   				$("#entertimepopup1").html(msg);
				document.getElementById("entertimepopup1").style.display='block';
				getPastweek(Weekdate);
								
			}
					
						}
			
			});

			
}
function getPastweek(selectsweek){ 
  if(selectsweek!='')
  {  
   var selectweek = selectsweek  ;    
   
 
  var GetSplit=selectweek.split("/");  
  var selectmonth=GetSplit[0];
  var selectday=GetSplit[1];
  var selectyear=GetSplit[2];
  var GetFullDate=selectyear+'/'+ selectmonth + '/' + selectday;
   
  for (var i = 0; i < 7; i++) {
var days = i;
var dating = new Date(GetFullDate);
var res = dating - (days * 24 * 60 * 60 * 1000);
var d = new Date(res);
var month = d.getMonth() + 1;
var day = d.getDate();

var output = d.getFullYear() + '/' +
    (month < 10 ? '0' : '') + month + '/' +
    (day < 10 ? '0' : '') + day; 

 if(i=='0')
	{
	 var SunDate = month < 10 ? '0' + month : month;
	 var finalday= day < 10 ? '0' + day : day;
		$("#Setsun").html(SunDate + '/' + finalday);	
 
 }
 
 if(i=='1')
	{
	 var satDate = month < 10 ? '0' + month : month;
	 var finalday= day < 10 ? '0' + day : day;
		$("#Setsat").html(satDate + '/' + finalday);	
 
 }
 
 
 
 if(i=='2')
	{
	 var FriDate = month < 10 ? '0' + month : month;
	 var finalday= day < 10 ? '0' + day : day;
		$("#Setfri").html(FriDate + '/' + finalday);

 }
 
 if(i=='3')
	{
	 var ThuDate = month < 10 ? '0' + month : month;
	 var finalday= day < 10 ? '0' + day : day;
		$("#Setthu").html(ThuDate + '/' + finalday);

 }
 
 if(i=='4')
	{
	 var WedDate = month < 10 ? '0' + month : month;
	 var finalday= day < 10 ? '0' + day : day;
		$("#Setwed").html(WedDate + '/' + finalday);	
 
 }
 
 if(i=='5')
	{
	 var TueDate = month < 10 ? '0' + month : month;
	 var finalday= day < 10 ? '0' + day : day;
		$("#Settue").html(TueDate + '/' + finalday);	
 
 }
 
 if(i=='6')
	{
	 var MonDate = month < 10 ? '0' + month : month;
	 var finalday= day < 10 ? '0' + day : day;
		$("#Setmon").html(MonDate + '/' + finalday);	
 
 } 
 
 
 }
	  
 
}

}
</script>
        <meta charset="UTF-8">
        <title></title>
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
                
                    if(trim($_SESSION['ClientSessionObject']['logType']) == "clientmgr_login")
                    {                          
		?>
                    <span class="username-lbl">
                    <?php echo $_SESSION['ClientSessionObject']['Last_Name']." ".$_SESSION['ClientSessionObject']['First_Name'];?>	
                    </span>			
                    
                    <span class="normal-lbl">
                    <?php echo $dataClient['customer_name'];?>	
                    </span>
                    <?php
                    }	 
                    ?>
		</div>
            <div class="right">				
				<ul>					
					<li>
						<a href="logout.php?func=logout" class="logout_btn"><span></span>Logout</a>
						
					</li>
					
				</ul>
		</div>
				
		<div class="clear"></div>
	
        </div>	

        </div>
        <div class="main" id="container_style" style="width:1112px;">
            <form name = "frmTimeExpense" action = "ClientManagerview.php" method= "post">
            <table cellpadding="0" cellspacing="0" border="0" width="650px" align="center" style="margin:20px auto;">
          
                <tr>			             																				
                    <td style="padding-right: 10px;" nowrap="nowrap">
			<label>Employee Status</label>
			<select  name="empstatus" id="empstatus"  style="width:84px;">
				<option value='AL' <?php if($_SESSION['empstatus'] =="AL") echo "Selected"; ?>>All</option>
				<option value='H' <?php if($_SESSION['empstatus'] =="H") echo "Selected"; ?>>Active</option>
				<option value='T' <?php if($_SESSION['empstatus'] =="T") echo "Selected"; ?>>Termed</option>										
				</select>				
                    </td>
                    
                    <td style="padding-right: 1px;" nowrap="nowrap">
			<label>Timesheet Status</label>
			<select  name="status" id="status"  style="width:140px;">
                            <option value='S'<?php if($_SESSION['status'] =="S") echo "Selected"; ?>>Awaiting Approval</option>
                            <option value='A' <?php if($_SESSION['status'] =="A") echo "Selected"; ?>>Approved</option>
                            <option value='R' <?php if($_SESSION['status'] =="R") echo "Selected"; ?>>Declined</option>
                            <option value='V' <?php if($_SESSION['status'] =="V") echo "Selected"; ?>>Reviewed by Acro</option>
                            <option value='N' <?php if($_SESSION['status'] =="N") echo "Selected"; ?>>Not Submitted</option>
                            <option value='AL' <?php if($_SESSION['status'] =="AL") echo "Selected"; ?>>All</option>
			</select>				
                    </td>
                    
                    <td style="padding-right: 12px;" nowrap="nowrap">
			<label style="margin:0 0 0 15px;">Weekending Date </label>
                        <input type="text" autocomplete="off" name="weekEndingDate" id="startDateSearch" value="<?php echo $Displaydate;?>"  style="width:85px" />
                    </td>
                    <td><input type="submit" id="viewTimeExpenseHistoryButton" name="viewTimeExpenseHistoryButton" value="Search" class="btn_style" style="margin:0 0 0 20px;"  /></td>
                </tr>
            </table>
              </form>
            <div class="report_paging center_div_big" align="right" style="margin-bottom:10px;"><?php if($nr>0) { echo "Total Records:&nbsp;".$nr."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";  } ?></div>
            <div id="SuccessMsg">
			<?php 
				
			if(isset($_GET['msg'])) {
				echo "<b class='success_msg'>".$_GET['msg']."</b>";
			}				
			?>
			
			<?php if(isset($_GET['error'])) {
				echo "<b class='error_msg'>".$_GET['error']."</b>";
			}				
			?>
			
			
			
			</div>
                <?php
            if ($nr > 0)
            {
            ?>
            <table cellpadding="0" cellspacing="0" border="0" align="center" class="gridview-ctrl center_div_big gridview-hover" style="width:1068px">
                <?php if($_SESSION['status'] == "S" || $_SESSION['status'] == "A" || $_SESSION['status'] == "R" || $_SESSION['status'] == "V" || $_SESSION['status'] == "AL"){ ?>
               <tr>
                    <th>Employee Name</th>
                    <th>W.E</th>
                    <th>Submited On</th>
                    <th>Timesheet Status</th>
                    <th>Curent Emp. Status</th>
                    <th>Timesheet</th>
                    <th>Expense</th>
                    <th>View Timesheet</th>
                    
                  
                </tr>
                <?php }
                if ($_SESSION['status'] == "N") {
                ?>
                
                <tr>
                    <th>Employee Name</th>
                    <th>Client Name</th>
                    <th>W.E</th>
            
                    <th>Timesheet Status</th>
                    <th>Curent Emp. Status</th>
                    <th>Timesheet</th>
                    <th>Expense</th>
                    <th>View Timesheet</th>
                    
                  
                </tr>
                <?php } 
                $i = 0;
                 while($data = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)){ 
                     $counter=0;
                        $i++;
                if($_SESSION['status'] == "S" || $_SESSION['status'] == "A" || $_SESSION['status'] == "R" || $_SESSION['status'] == "V" || $_SESSION['status'] == "AL" ){ ?>
                 
                <tr>
                    <td> <?php echo $data['last_name']." ".$data['first_name'];?></td>
                      <td> <?php echo date_format($data['wedte'], 'm/d/Y'); ?>  </td>                    
                    <td> <?php echo date_format($data['sub'], 'm/d/Y H:i A'); ?> </td>
                    <td> <?php 
                    	if($data['status'] =="Submitted"){
			echo "<span class='status_icon_check'>Submitted</span>";
			}
else                    if($data['status'] =="Approved"){
			echo "<span class='status_icon_check'>Approved</span>"; }

			else if($data['status'] =="Viewed By Acro"){
			echo "<span class='status_icon_view'>Viewed By Acro</span>";
			}
                        else if($data['status'] =="Ignored"){
			echo "<span class='not_submit_icon'>Ignored By Acro</span>";
			}
                        else if($data['status'] =="Declined"){
			echo "<span class='status_icon_reject'>Declined</span>";
			}
                        else {
			echo $data['status'];
			}
                    
                    ?> </td>
                    <td> 
                        <?php 
                        if($data['e.empstatus']=="Terminated")
			{
			echo "<span class='terminated_icon'>".$data['e.empstatus']."</span>";
			}
				
			
			else { echo  "<span class='status_icon_check'>Active</span>"; }
                        ?> 
                    </td>
                    <td>
                        <?php
                        $pathTimeSheet= $data['rid']."_".first(explode(".", $data['timeSheetDocName'])).".".last(explode(".", $data['timeSheetDocName']));
			$pathExpenseSheet=$data['rid']."_".first(explode(".", $data['expenseSheetDocName'])).".".last(explode(".", $data['expenseSheetDocName']));
							
			if($data['timeSheetDocName']!="")
                            {                      
                                 $getTimeheetExts	  = end(explode(".", $data['timeSheetDocName']));
				 $getTimeheetExt =strtolower($getTimeheetExts);
                    
                        ?>
                        <a title='<?php echo $data['timeSheetDocName']; ?>'   href='#' onClick="startDownload('<?php echo $uploadPath; ?>' , '<?php echo $pathTimeSheet;?>')" >
                        <?php if($getTimeheetExt=='jpg' || $getTimeheetExt=='jpeg'){echo '<img src="images/jpg-icon.png" border="0"  width="20px" />'; } else if($getTimeheetExt=='pdf') {echo '<img src="images/pdf-icon.png" border="0"  width="20px" />'; } ?></a>					
                        <?php				
				}		
				?>
                    </td>
                    
                    <td>
                     <?php
			if($data[expenseSheetDocName]!=""){
				
                            $getExpenseTimeheetExts = end(explode(".", $data[expenseSheetDocName]));	
                            $getExpenseTimeheetExt	=strtolower($getExpenseTimeheetExts);
                    ?>
                    <a title='<?php echo $data[expenseSheetDocName];?>' href='#' onClick="startDownload('<?php echo $uploadPath; ?>' , '<?php echo $pathExpenseSheet;?>')" >
                    <?php if($getExpenseTimeheetExt=='jpg' || $getExpenseTimeheetExt=='jpeg'){echo '<img src="images/jpg-icon.png" border="0"  width="20px" />'; } else if($getExpenseTimeheetExt=='pdf') {echo '<img src="images/pdf-icon.png" border="0" width="20px" />'; }?>
                    </a>
                    <?php
			}				
                    ?>
                    </td>
                    
                    <?php
                    if($data['TimePortEntrystyle']!='0' &&  $data['TimePortEntrystyle']!='') { ?>
                    <td align = 'centre'>
                        <a  href="javascript:void(0);" onclick="GetTimeSheetOnClick('<?php echo date_format($data['wedte'], 'm/d/Y')?>','<?php echo $data['empplaceid'];?>');" ><img src="images/file_edit.gif" width="20px"></a>
			<?php }else {echo "<td align='center'>&nbsp;</td>";}?>
                  
                 
                    
                </tr>
                <?php
                       }
                if($_SESSION['status'] == "N"){ ?>
                 
                    <tr>
			
			<td>
				<?php			
				 echo  $data['last_name']." ".$data['first_name'];						
				?>	
			</td>
			<td>
			<?php 
			echo  $data['customer_name'];	
			?>
			</td>
			<td>
		
		<?php echo  date("m/d/Y", strtotime($_SESSION['weekEndingDate'])); ?>
		</td>
					
			<td>
						<?php echo "<span class='not_submit_icon'>Not Submitted</span>"; ?>	 
			</td>
				<td align="center"><?php

			if($data['e.empstatus']=="Terminated")
			{
			echo "<span class='terminated_icon'>".$data['e.empstatus']."</span>";
			}
				
			
			else { echo  "<span class='status_icon_check'>Active</span>"; }
			?></td>		
			<td align="center">
				&nbsp;
			</td>
			<td align="center">
			<?php
				if($data[expenseSheetDocName]!=""){
				 $getExpenseTimeheetExts	  = end(explode(".", $data[expenseSheetDocName]));	
				 $getExpenseTimeheetExt	=strtolower($getExpenseTimeheetExts);
				?>				
				<a title='<?php echo $data[expenseSheetDocName];?>' href='#' onClick="startDownload('<?php echo $uploadPath; ?>' , '<?php echo $pathExpenseSheet;?>')" >
				<?php if($getExpenseTimeheetExt=='jpg' || $getExpenseTimeheetExt=='jpeg'){echo '<img src="images/jpg-icon.png" border="0"  width="20px" />'; } else if($getExpenseTimeheetExt=='pdf') {echo '<img src="images/pdf-icon.png" border="0" width="20px" />'; }?></a>				
<?php				
				}			
				?>
			</td>
			<td align='center'>
				
				
			</td>
		</tr>
                <?php
                
                }
                 
                if($counter == 1) { $counter=0; } else { $counter = 1; }
                                } ?>
            </table>
            <?php            
            }
            else
            {
                if($_GET['func']!= "search" OR $nr==0)	
		 echo "<center><b>No Record Found</b></center>";
            }
            ?>
            <input type = "hidden" name ="st_pos"/>
	<div class="clear"></div>
	<div class="report_paging center_div_big" align="right"><?php if($nr>0) { echo "Total Records:&nbsp;".$nr."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; } ?></div>
      
        </div>
    </body>
</html>
<?php require_once("EnterTimePopup1.php"); ?>