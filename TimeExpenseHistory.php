<?php 
session_start();
require_once("includes/db_connection_irm.php");
require_once("includes/functions.php");
require_once("includes/headerEmployee.php");
require_once("ConfigurationRead.php");


$uploadPath = getKeyValue("UploadPath","upload.config");
$iconn 		 = getIRMDatabaseConnection();
define('MAX_REC_PER_PAGE', 50);
$rowCount = 0;
$date		= date('m/d/Y');
$start_date = '';
$end_date 	= '';
$start 		= 0;
$end 		= 50;

/*** Download attachment file  ***/
if (isset($_GET['file'])) {
		$file = $_GET['file'];
		if (file_exists($file) && is_readable($file) ) {
		header('Content-type: application/pdf');
		header("Content-Disposition: attachment; filename=\"$file\"");
		readfile($file);
		}
		
}
$empPlacementId = $_SESSION['UserSessionObject']['empPlacementId'];
	

if($_GET['func'] == "search"){
	unset($_SESSION['startDateSearch']);
	unset($_SESSION['endDateSearch']);
	unset($_SESSION['startDateSearchShow']);
	unset($_SESSION['endDateSearchShow']);
	
}


if(isset($_REQUEST['startDateSearch']) && !empty($_REQUEST['startDateSearch']) && $_REQUEST['endDateSearch'] &&  !empty($_REQUEST['endDateSearch'])){

$_SESSION['startDateSearchShow'] = $_REQUEST['startDateSearch'];
$_SESSION['endDateSearchShow'] = $_REQUEST['endDateSearch'];


$start_date	= convertDatetoYYDDMMFormat(trim($_REQUEST['startDateSearch']));
$_SESSION['startDateSearch'] = $start_date;

$end_date =	convertDatetoYYDDMMFormat(trim($_REQUEST['endDateSearch']));
$_SESSION['endDateSearch'] = $end_date;
}

$SQL    = "SELECT * FROM tblAcroPortRecords WHERE weekEndingDate between '$_SESSION[startDateSearch]' AND '$_SESSION[endDateSearch]' AND empPlacementId='$empPlacementId'";
$result = sqlsrv_query($iconn, $SQL , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) or die("INSERT ERROR During Search");			
$nr     = sqlsrv_num_rows($result);


if (isset($_GET['pn'])) { 
	$pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); 


	} else { 
	$pn = 1;
	}
	$itemsPerPage = 50; 
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

$query_report = "WITH tblAcro_Procedure AS
		(
		SELECT *,
		ROW_NUMBER() OVER (ORDER BY submittedOn desc ) AS 'RowNumber'
		FROM tblAcroPortRecords
		where weekEndingDate between '$_SESSION[startDateSearch]' AND '$_SESSION[endDateSearch]' AND empPlacementId='$empPlacementId'
		) 
		SELECT * 
		FROM tblAcro_Procedure
		WHERE RowNumber BETWEEN($pn -1) * $end + 1 AND((($pn -1) * $end + 1) + $end) - 1";	
// echo "<br><br>:::".$query_report;


$resultSet = sqlsrv_query($iconn, $query_report , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) or die("INSERT ERROR During Search");			
$nr11 =	sqlsrv_num_rows($resultSet);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<meta name="x-blackberry-defaultHoverEffect" content="false" />
<title>AcroTrac</title>
    
<link rel="stylesheet" href="template.css" type="text/css" />
<link rel="stylesheet" href="datepicker/jquery.ui.all.css">
<script src="datepicker/jquery-1.4.4.js"></script>
<script src="datepicker/jquery.ui.core.js"></script>
<script src="datepicker/jquery.ui.widget.js"></script>
<script src="datepicker/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="datepicker/demos.css">                                       
<script>
function startDownload(folderName,fileName)  
{	
	var sitename ="http://acrocorp.com/timeport";	
	  var url=sitename+"/"+folderName+ "/"+fileName;
       window.open(url, "", "width=1000, height=600"); 
}  
</script>

<script type="text/javascript">
$(document).ready(function() {  
	  $('#billcomments').keypress(function(event) {
           var maxLength = 256;
           var text = $(this).val();
           var textLength = text.length;
           if (textLength > maxLength) {
               $(this).val(text.substring(0, (maxLength)));
				event.preventDefault();
           }
       });
}); 
</script>

<script>	
	$(function(){
	var pickerOpts = {
		dateFormat:'mm/dd/yy'
	};  
	$("#startDateSearch").datepicker(pickerOpts);
	$("#endDateSearch").datepicker(pickerOpts);
});




</script>

<script>
	$(document).ready(function() {
	
	function compareDates(from, to)
	{
	
		from = from.split('/');
		from = new Date(from[2], (from[0]-1), from[1]);
		to = to.split('/');
		to = new Date(to[2], (to[0]-1), to[1]);
		if((from.getTime()-to.getTime())>0) 
		{
		return true;
		}
		else
		{
		return false;
		}
	}
	
	function OneYearValidate(from, to)
	{
	
		from = from.split('/');
		from = new Date(from[2], (from[0]-1), from[1]);
		to = to.split('/');
		to = new Date(to[2], (to[0]-1), to[1]);
		if((from.getTime()-to.getTime())>0) 
		{
		return true;
		}
		else
		{
		return false;
		}
	}
	
	
	function parseDate(str) {
    var mdy = str.split('/')
    return new Date(mdy[2], mdy[0]-1, mdy[1]);
	}

	function daydiff(first, second) {
		return (second-first)/(1000*60*60*24);
	}



		
	$("#submitHistoryButton").click(function() {	
			
		$("#ErrorMsg").html("");	

				
				if($("#startDateSearch").val() == "")
				{
					$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b> Please enter start date</span>");										
					return false;
				}
				else if($("#endDateSearch").val() == "")
				{
					$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b> Please enter end date</span>");					
					return false;
					
				}				
			if($("#startDateSearch").val() != "" && $("#endDateSearch").val() != "")
				{			
					var compare= compareDates($("#startDateSearch").val(), $("#endDateSearch").val())
				
					if(compare)
					{		
						$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b>Start date must be before the end date</span>");
						return false;
					}				
				
				}
				if($("#startDateSearch").val() != "" && $("#endDateSearch").val() != ""){				
				
				
				var diff = daydiff(parseDate($('#startDateSearch').val()), parseDate($('#endDateSearch').val()));
				
				if(diff>365)
					{		
						$("#ErrorMsg").html("<span class='error_msg'><b>Error :</b>Date range should be with in one year</span>");
						return false;
					}
					return true;				
				}
				
				
				return false;
		});	
	});
	
	
	
	
	
	function GetTimeSheetOnClick(Weekdate)
{		
       var empPlacementId = $("#empPlacementId").val();
	   var str=Weekdate;
	  dataString= 'weekendingdate='+str + '&empPlacementId='+empPlacementId;	 
	  
$.ajax({ 
		type: "POST",
		url: "EnterTimePopup.php",
		data: dataString,		
		success: function(msg){	
	       msg = $.trim(msg);
		   
if(msg!="")
{		
   				$("#entertimepopup1").html(msg);
				document.getElementById("entertimepopup1").style.display='block';
				getPastweek(Weekdate)
				
				
				
			
			
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
		/***  Employee Login Label ***/
		if(trim($_SESSION['UserSessionObject']['logType']) == "employee_login"){
		?>
			<span class="username-lbl">
			<?php echo $_SESSION['UserSessionObject']['employee_lf_name'];?>	
			</span>

			<span class="weekendingdate-lbl">
			<?php echo $_SESSION['UserSessionObject']['client_name']; ?>	
			</span>
                      <span class="normal-lbl">
                        <?php 
			echo  $_SESSION['UserSessionObject']['client_mgr_firstname'];
                        echo " ";
                        echo  $_SESSION['UserSessionObject']['client_mgr_lastname']; 
                               
                        ?>
                        </span>
			<?php			 
		}	 
	    ?>
		</div>
		
			
			<div class="right">
				<ul>
					<li>
						<a href="TimeExpenseSheet.php" class="back_btn"><span></span>Back</a>
					</li>
					<li>
						<a href="ChangePassword.php" class="logout_btn" style="padding: 1px 8px 1px 8px;">Change Password</a>
					</li>
					<li>
						<a href="logout.php?func=logout" class="logout_btn"><span></span>Logout</a>
					</li>
				</ul>
			</div>
			
			
			
			
		<div class="clear"></div>
	</div>
</div>		
	<div class="main" id="container_style">	
	<div id="ErrorMsg"></div>	
		<form action = "TimeExpenseHistory.php" method= "post">			
		
		<table cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0px auto 20px auto; width:440px;" >
            <tr>
				<td align="center" colspan="5"><span class="page_heading">View Time/Expense History</span></td>
			</tr>
			<tr>
                <td nowrap="nowrap"><label style="margin:0 3px 0 0px;">Start Date</label></td>				
				<td>
					<input type="text" autocomplete="off" name="startDateSearch" id="startDateSearch" value="<?php if(!empty($_SESSION['startDateSearchShow'])){echo $_SESSION['startDateSearchShow'];}else{ print(date("m/d/Y",strtotime("-3 month"))); } ?>"  style="width:85px" />
				</td>
				<td nowrap="nowrap"><label style="margin:0 3px 0 20px;">End Date</label></td>				
				<td>
					<input type="text" autocomplete="off" name="endDateSearch" id="endDateSearch" value="<?php if(!empty($_SESSION['endDateSearchShow'])){echo $_SESSION['endDateSearchShow'];}else{print(date("m/d/Y", strtotime('today')));} ?>"  style="width:85px" />
				</td>
				<td><input type="submit" name="submitHistoryButton" id="submitHistoryButton" value="Search" class="btn_style" style="margin:0 0 0 20px;" /></td>
            </tr>			
			
        </table>
		
			<div class="report_paging center_div_big" align="right"><?php if($nr>0) { echo "Total Records:&nbsp;".$nr."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo $paginationDisplay;} ?></div>
	
<?php
	  if($nr > 0){
	  
	$SQL1="SELECT * FROM candidate where row_id=".$_SESSION['UserSessionObject']['employee_id']; 
	$rs = sqlsrv_query($iconn, $SQL1);
	$data = sqlsrv_fetch_array( $rs, SQLSRV_FETCH_ASSOC);	
	$firstname= $data['first_name'];
	$lastname= $data['last_name'];
		
	  
	?>
	
	<table cellpadding="0" cellspacing="0" border="0" align="center" class="gridview-ctrl center_div_big gridview-hover" style="margin:10px auto;">
	<tr>
		<th width="75px">W.E.</th>
		<th width="140px">Submitted On</th>
		<th>Submitted By</th>
		<th width="135px">Status</th>
		<th width="85px">Time Sheet</th>
		<th width="75px">Expense</th>
		<th width="93px">View Timesheet</th>
	</tr>		
	
		<?php
		while($data = sqlsrv_fetch_array($resultSet, SQLSRV_FETCH_ASSOC)){
		$counter=0;
	
		?>
		<tr>
			<td><?php echo date_format($data['weekEndingDate'], 'm/d/Y'); ?></td>
			<td><?php echo date_format($data['submittedOn'], 'm/d/Y H:i A'); ?></td>
			<td>
			<?php	 echo $lastname." ".$firstname;		
		//	echo id_string($iconn,"candidate", $data['submittedBy'], "row_id", "last_name")." ";			
		//	echo id_string($iconn,"candidate", $data['submittedBy'], "row_id", "first_name");		
			?>	
			</td>
			<td><?php 
				if(strcasecmp($data['status'],"S") == 0){
					echo "<span class='status_icon_check'>Submitted</span>";
				}
				else if(strcasecmp($data['status'],"I") == 0){
					echo "<span class='status_icon_view'>Viewed By Acro</span>";
				}
				
				else if(strcasecmp($data['status'],"V") == 0){
					echo "<span class='status_icon_view'>Viewed By Acro</span>";
				}
                                else if(strcasecmp($data['status'],"A") == 0){
					echo "<span class='status_icon_check'>Approved</span>";
				}
                                else if(strcasecmp($data['status'],"R") == 0){
					echo "<span class='status_icon_reject'>Declined</span>";
				}
				?></td>
			<td align='center'>
			<?php				
				$pathTimeSheet    = $data[rowId]."_".first(explode(".", $data[timeSheetDocName])).".".last(explode(".", $data[timeSheetDocName]));
				$pathExpenseSheet    = $data[rowId]."_".first(explode(".", $data[expenseSheetDocName])).".".last(explode(".", $data[expenseSheetDocName]));
				//echo $pathExpenseSheet;
				if($data[timeSheetDocName]!=""){
							
	             $getTimeheetExts	  = end(explode(".", $data[timeSheetDocName]));	
				  $getTimeheetExt=strtolower($getTimeheetExts);
					
?>				
					<a title='<?php echo $data[timeSheetDocName]; ?>' href='#' onClick="startDownload('<?php echo $uploadPath; ?>' , '<?php echo $pathTimeSheet;?>')" >
					 <?php if($getTimeheetExt=='jpg' || $getTimeheetExt=='jpeg'){echo '<img src="images/jpg-icon.png" border="0"  width="20px" />'; } else if($getTimeheetExt=='pdf') {echo '<img src="images/pdf-icon.png" border="0"  width="20px" />'; } ?></a>					
<?php	
				}
				?></td>
			<td align='center'><?php
			    if($data[expenseSheetDocName]!=""){
				 $getExpenseTimeheetExts	  = end(explode(".", $data[expenseSheetDocName]));
				$getExpenseTimeheetExt=strtolower($getExpenseTimeheetExts);				 
?>				
				<a title='<?php echo $data[expenseSheetDocName];?>' href='#' onClick="startDownload('<?php echo $uploadPath; ?>' , '<?php echo $pathExpenseSheet;?>')" >
				<?php if($getExpenseTimeheetExt=='jpg' || $getExpenseTimeheetExt=='jpeg'){echo '<img src="images/jpg-icon.png" border="0"  width="20px" />'; } else if($getExpenseTimeheetExt=='pdf') {echo '<img src="images/pdf-icon.png" border="0" width="20px" />'; }?></a>				
<?php				
				}
			?></td>
		 <?php if($data[TimePortEntrystyle]!="") {?>
<td align='center'>
				<a href="javascript:void(0);" onclick="GetTimeSheetOnClick('<?php echo date_format($data['weekEndingDate'], 'm/d/Y')?>','<?php echo $data['row_id'];?>');" ><img src="images/file_edit.gif" width="20px"></a>
			<?php }else {echo "<td align='center'>&nbsp;</td>";}?>

		</tr>		
	<?php
		if($counter == 1) { $counter=0; } else { $counter = 1; } 
		
		
		
		}	// end of while loop
	echo "</table>";	
	} // end of nr
	 else{
		if($_GET['func']!= "search")	
		 echo "<center><b>No Record Found</b></center>";
	 }
	
	
	
?>

<input type = "hidden" name ="st_pos"/>

		<div class="report_paging center_div_big" align="right"><?php if($nr>0) { echo "Total Records:&nbsp;".$nr."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; echo $paginationDisplay;} ?></div>
		<input type="hidden" id="empPlacementId" name="empPlacementId" value = "<?php echo $_SESSION['UserSessionObject']['empPlacementId']; ?>" />
</form>
</div>




</body>
</html>
<?php



require_once("EnterTimePopup1.php");

 sqlsrv_close($iconn); ?>