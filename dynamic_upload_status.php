<?php 
session_start();
require_once("includes/db_connection_irm.php");
require_once("includes/functions.php");
require_once("ConfigurationRead.php");
$iconn =getIRMDatabaseConnection();

$uploadPath = getKeyValue("UploadPath","upload.config");

$weekendingdate		="";
$empPlacementId		="";
if(isset($_POST['weekendingdate'])){
	$weekendingdate= $_POST['weekendingdate'];
}
if(isset($_POST['empPlacementId'])){
	$empPlacementId= $_POST['empPlacementId'];
}

$SQL="SELECT rowId,timeSheetDocName, expenseSheetDocName, status FROM tblAcroPortRecords WHERE weekEndingDate= '$weekendingdate' and empPlacementId = '$empPlacementId'";
$result = sqlsrv_query($iconn, $SQL , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
$data = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
if(sqlsrv_num_rows($result) == 1){
?>
<table cellpadding='0' cellspacing='0' border='1' align='right' class='gridview-ctrl'>
<tr>
<th>Time Sheet</th>
<th>Expense Sheet</th>
<th>Status</th>
<tr>
<?php
	$pathTimeSheet = "";
	$pathExpenseSheet = "";
	$pathTimeSheetDb = trim($data[timeSheetDocName]);
	$pathExpenseSheetDb = trim($data[expenseSheetDocName]);
	if($pathTimeSheetDb!=""){
		$pathTimeSheet    = $data[rowId]."_".first(explode(".", $data[timeSheetDocName])).".".last(explode(".", $data[timeSheetDocName]));
	}
	if($pathExpenseSheetDb!=""){
		$pathExpenseSheet = $data[rowId]."_".first(explode(".", $data[expenseSheetDocName])).".".last(explode(".", $data[expenseSheetDocName]));
	}
?>	
<td align='center'>
	<?php
		if($pathTimeSheetDb!=""){
		$getTimeSheetExts	  = end(explode(".", $pathTimeSheet));
		$getTimeSheetExt	  = strtolower($getTimeSheetExts);
			
	
?>		
		<a title="<?php echo $data[timeSheetDocName]; ?>" onClick="startDownload('<?php echo $uploadPath; ?>' , '<?php echo $pathTimeSheet;?>')" href="#"> <?php if($getTimeSheetExt=='jpg' || $getTimeSheetExt=='jpeg'){echo '<img src="images/jpg-icon.png" border="0" />'; } else if($getTimeSheetExt=='pdf') {echo '<img src="images/pdf-icon.png" border="0" />'; } ?>  </a>
<?php			
			
		}	
	?>
</td>
<td align='center'>
	<?php
	if($pathExpenseSheetDb!=""){
	$getExpenseSheetExt	  = end(explode(".", $pathExpenseSheet));
	?>
		<a title="<?php echo $data[expenseSheetDocName]; ?>" onClick="startDownload('<?php echo $uploadPath; ?>' , '<?php echo $pathExpenseSheet;?>')" href="#">  <?php if($getExpenseSheetExt=='jpg' || $getExpenseSheetExt=='jpeg'){echo '<img src="images/jpg-icon.png" border="0" />'; } else if($getExpenseSheetExt=='pdf') {echo '<img src="images/pdf-icon.png" border="0" />'; } ?> </a>
<?php		
	}	
	?>
</td>
<td>
<?php 
	if(strcasecmp($data['status'],"S") == 0){
		echo "<span class='status_icon_check'>Submitted</span>";
	}else if(strcasecmp($data['status'],"V") == 0){
		echo "<span class='status_icon_view'>Viewed By Acro</span>";
	}		
else if(strcasecmp($data['status'],"I") == 0){
		echo "<span class='status_icon_view'>Viewed By Acro</span>";
	}
        else if(strcasecmp($data['status'],"A") == 0){
		echo "<span class='status_icon_check'>Approved</span>";
	}
         else if(strcasecmp($data['status'],"R") == 0){
		echo "<span class='status_icon_reject'>Rejected</span>";
	}

	
?>
</td>
		
</tr>





</table>
<?php
}else{
	echo "";
}
sqlsrv_free_stmt($result);
?>
