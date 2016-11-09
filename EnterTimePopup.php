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

   $SQL="SELECT * FROM tblAcroPortRecords WHERE weekEndingDate= '$weekendingdate' and empPlacementId = '$empPlacementId'";  
$result = sqlsrv_query($iconn, $SQL , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
$data = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

$gettotalVal=sqlsrv_num_rows($result);
if($data[TimePortEntrystyle]==2)
{

$stTotal=$data[monst]+$data[tuest]+$data[wedst]+$data[thust]+$data[frist]+$data[satst]+$data[sunst];
$otTotal=$data[monot]+$data[tueot]+$data[wedot]+$data[thuot]+$data[friot]+$data[satot]+$data[sunot];
$dtTotal=$data[mondt]+$data[tuedt]+$data[weddt]+$data[thudt]+$data[fridt]+$data[satdt]+$data[sundt];

$MonDailyTotal=$data[monst]+$data[monot]+$data[mondt];
$TueDailyTotal=$data[tuest]+$data[tueot]+$data[tuedt];
$WedDailyTotal=$data[wedst]+$data[wedot]+$data[weddt];
$ThuDailyTotal=$data[thust]+$data[thuot]+$data[thudt];
$FriDailyTotal=$data[frist]+$data[friot]+$data[fridt];
$SatDailyTotal=$data[satst]+$data[satot]+$data[satdt];
$SunDailyTotal=$data[sunst]+$data[sunot]+$data[sundt];
$FinalTotal1=$stTotal+$otTotal+$dtTotal;
$FinalTotal= number_format((float)$FinalTotal1, 2, '.', '');
}
else{

//ST Total 
 $stTotal=getWeeklyTotal($data[monst],$data[tuest],$data[wedst],$data[thust],$data[frist],$data[satst],$data[sunst]);  	   
//OT Total 
 $otTotal=getWeeklyTotal($data[monot],$data[tueot],$data[wedot],$data[thuot],$data[friot],$data[satot],$data[sunot]); 
//DT total 
 $dtTotal=getWeeklyTotal($data[mondt],$data[tuedt],$data[weddt],$data[thudt],$data[fridt],$data[satdt],$data[sundt]);  
		
		

$MonDailyTotal=getDailyTotal($data[monst],$data[monot],$data[mondt]);  
$TueDailyTotal=getDailyTotal($data[tuest],$data[tueot],$data[tuedt]); 
$WedDailyTotal1=getDailyTotal($data[wedst],$data[wedot],$data[weddt]);
$ThuDailyTotal=getDailyTotal($data[thust],$data[thuot],$data[thudt]);
$FriDailyTotal=getDailyTotal($data[frist],$data[friot],$data[fridt]); 
$SatDailyTotal=getDailyTotal($data[satst],$data[satot],$data[satdt]);
$SunDailyTotal=getDailyTotal($data[sunst],$data[sunot],$data[sundt]);

 $FinalTotal=getDailyTotal($stTotal,$otTotal,$dtTotal);



}






?>




<form name="TimeSheetDetail" id="TimeSheetDetail" method="post" style="height:478px;width:719px;">
<h1 style=" margin: 0;
  padding: 0 0 8px 0;
  font-size: 12px;"><?php			
			echo id_string($iconn,"candidate", $data['submittedBy'], "row_id", "last_name")." ";			
			echo id_string($iconn,"candidate", $data['submittedBy'], "row_id", "first_name");			
			?>	</h1> 
			
			<?php if($data['ReviewedOn']!="")
		{ echo '<h1 style="float: left;font-size: 12px; margin-top: -23px; margin-left:150px;">Reviewed on: '.date_format($data['ReviewedOn'], 'm/d/Y H:i A'); } ?></h1>
			
			
			<h1 style="float: left;font-size: 12px; margin-top: -23px; margin-left:375px;">Submitted on: <?php echo date_format($data['submittedOn'], 'm/d/Y H:i A'); ?></h1><h1 style="float: right;font-size: 12px; margin-top: -23px;">W.E: <?php echo $weekendingdate ?></h1>
<a onclick="document.getElementById('entertimepopup1').style.display='none';" class="popup-close" ></a>	

<table width="600px;" border="1" class="TimeSet">

<tbody><tr>

<th><label>Days/Time<label></label></label></th>

<th><label>Mon<br><span  id="Setmon"></span><label></label></label></th>
    
 <th><label>Tue <br> <span  id="Settue"></span></label></th>

<th><label>Wed <br><span  id="Setwed"></span></label></th>

<th><label>Thu <br><span  id="Setthu"></span></label></th>

<th><label>Fri <br> <span  id="Setfri"></span></label></th>

<th><label>Sat <br><span  id="Setsat"></span></label></th>

<th><label>Sun <br><span  id="Setsun"></span></label></th>

<th><label>Weekly Total</label></th>

</tr>

<tr>

<td style="width:100px;" nowrap="nowrap"><label>Straight Time</label></td>

<td><input type="text"    style="text-align:right;" value="<?php if($data[monst]==".00"){ echo "0.00"; } else { echo $data[monst]; }?>" readonly="readonly" placeholder=""></td>

<td><input type="text"   style="text-align:right;" value="<?php if($data[tuest]==".00"){ echo "0.00"; } else { echo $data[tuest]; }?>"  readonly="readonly" placeholder=""></td>

<td><input type="text"  style="text-align:right;"  value="<?php if($data[wedst]==".00"){ echo "0.00"; } else { echo $data[wedst]; }?>" readonly="readonly" placeholder=""></td>

<td><input type="text"   style="text-align:right;" value="<?php if($data[thust]==".00"){ echo "0.00"; } else { echo $data[thust]; }?>" readonly="readonly" placeholder=""></td>

<td><input type="text"  style="text-align:right;" value="<?php if($data[frist]==".00"){ echo "0.00"; } else { echo $data[frist]; }?>" readonly="readonly" placeholder=""></td>

<td><input type="text"  style="text-align:right;" value="<?php if($data[satst]==".00"){ echo "0.00"; } else { echo $data[satst]; }?>" readonly="readonly" placeholder=""></td>

<td><input type="text"  style="text-align:right;" value="<?php if($data[sunst]==".00"){ echo "0.00"; } else { echo $data[sunst]; }?>" readonly="readonly" placeholder=""></td>




 <td align="right" style="font-size:17px;color:#444;"><?php echo number_format((float)$stTotal, 2, '.', '')   ?></td>

</tr>

 <tr>

        <td style="width:100px;"><label>Overtime</label></td>

        <td><input type="text"  style="text-align:right;" value="<?php if($data[monot]==".00"){ echo "0.00"; } else { echo $data[monot]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text"   style="text-align:right;" value="<?php if($data[tueot]==".00"){ echo "0.00"; } else { echo $data[tueot]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text"   style="text-align:right;" value="<?php if($data[wedot]==".00"){ echo "0.00"; } else { echo $data[wedot]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text"  style="text-align:right;"  value="<?php if($data[thuot]==".00"){ echo "0.00"; } else { echo $data[thuot]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text"   style="text-align:right; "value="<?php if($data[friot]==".00"){ echo "0.00"; } else { echo $data[friot]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text"  style="text-align:right;"  value="<?php if($data[satot]==".00"){ echo "0.00"; } else { echo $data[satot]; }?>" readonly="readonly" placeholder=""></td>

        
        <td><input type="text"   style="text-align:right;" value="<?php if($data[sunot]==".00"){ echo "0.00"; } else { echo $data[sunot]; }?>" readonly="readonly" placeholder=""></td>


        <td align="right" style="font-size:17px;color:#444;"><?php echo number_format((float)$otTotal, 2, '.', '')   ?></td>

    </tr>

 <tr>

        <td style="width:100px;"><label>Double Time</label></td>

        <td><input type="text"  style="text-align:right;"  value="<?php if($data[mondt]==".00"){ echo "0.00"; } else { echo $data[mondt]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text"  style="text-align:right;"  value="<?php if($data[tuedt]==".00"){ echo "0.00"; } else { echo $data[tuedt]; }?>"  readonly="readonly" placeholder=""></td>

        <td><input type="text"  style="text-align:right;"  value="<?php if($data[weddt]==".00"){ echo "0.00"; } else { echo $data[weddt]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text"  style="text-align:right;"  value="<?php if($data[thudt]==".00"){ echo "0.00"; } else { echo $data[thudt]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text"  style="text-align:right;" value="<?php if($data[fridt]==".00"){ echo "0.00"; } else { echo $data[fridt]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text"  style="text-align:right;" value="<?php if($data[satdt]==".00"){ echo "0.00"; } else { echo $data[satdt]; }?>" readonly="readonly"  placeholder=""></td>
        <td><input type="text"  style="text-align:right;"  value="<?php if($data[sundt]==".00"){ echo "0.00"; } else { echo $data[sundt]; }?>" readonly="readonly"  placeholder=""></td>

        <td align="right" style="font-size:17px;color:#444;"><?php echo number_format((float)$dtTotal, 2, '.', '') ;  ?></td>

    </tr>
<tr style="background-color:#C8D7E6">

<td align="right"><label>Daily Total</label></td><td align="right" style="font-size:17px; font-weight: bold;color:#444;"><?php echo number_format((float)$MonDailyTotal, 2, '.', '')   ?></td><td align="right" style="font-size:17px; font-weight: bold;color: #444;"><?php echo number_format((float)$TueDailyTotal, 2, '.', '')   ?></td><td align="right" style="font-size:17px; font-weight: bold;color:#444;"><?php echo number_format((float)$WedDailyTotal, 2, '.', '')   ?></td><td align="right" style="font-size:17px; font-weight: bold;color:#444;"><?php echo number_format((float)$ThuDailyTotal, 2, '.', '')   ?></td><td align="right" style="font-size:17px; font-weight:bold;color:#444;"><?php echo number_format((float)$FriDailyTotal, 2, '.', '')   ?></td><td align="right" style="font-size:17px; font-weight:bold;color:#444;"><?php echo number_format((float)$SatDailyTotal, 2, '.', '')   ?></td><td align="right" style="font-size:17px; font-weight:bold;color:#444;"><?php echo number_format((float)$SunDailyTotal, 2, '.', '')   ?></td>

          <!---<td colspan="8" align="right" style="padding:10px 0px 10px 0px; ">
                <label>Total All Hours</label>

           </td> -->

            <td align="right" >
                <b style="font-size:17px; font-weight: bold;color:#444;"><?php  echo number_format((float)$FinalTotal, 2, '.', '') ; ?></b>

            </td>


        </tr>
    


</tbody></table><br>
<span style="font-size: 11px;">Note:<?php
 if($data[TimePortEntrystyle]=='2'){ echo "Time  is in decimal format";  } else { echo "Time is in hh.mm format";}?><a  style="float:right;text-decoration:none;" title='conversion chart' href='#' onClick="timeformatpop();" >Time Conversion Table</a></span></span>
 <div style="margin-top:20px;"><label style="vertical-align: top;margin-top: 28px;">ACRO Comments:</label>
<textarea  readonly="readonly" name="timeSheetcomments" onkeyup="countChar(this)" maxlength="298" id="timeSheetcomments" style="width: 537px; height: 60px;background-color:#EFF7F7; margin-left:28px;"><?php echo $data[TimesheetComments]?></textarea>
 <!--<br> <bdo id="charNum" style="margin-left: 122px; font-size:10px;">200/200 characters remaining</bdo>-->
</div><br>

<label style="margin-top:34px; vertical-align:top;">Employee  Comments:</label> <textarea readonly="readonly" name="timeSheetempcmt" onkeyup="countChar(this)" maxlength="298" id="timeSheetempcmt" style="width: 539px; height: 60px;background-color:#EFF7F7;margin-left:4px;"><?php echo $data[comments]; ?></textarea> 

 
<div class="button-bar">

	<input type="button" value="Close" name="submit_upload_button" onclick="document.getElementById('entertimepopup1').style.display='none';"  class="btn_style"> 
</div>
</form>
