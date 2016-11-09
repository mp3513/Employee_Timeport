


<script type="text/javascript" src="js/jquery.min.js"></script>

<script type="text/javascript">pastweek();</script>

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

if($gettotalVal==1 && $data[TimePortEntrystyle]!=""){ 

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
$SunDailyTotal=$data[sunst]+$data[sunt]+$data[sundt];
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
$WedDailyTotal=getDailyTotal($data[wedst],$data[wedot],$data[weddt]);
$ThuDailyTotal=getDailyTotal($data[thust],$data[thuot],$data[thudt]);
$FriDailyTotal=getDailyTotal($data[frist],$data[friot],$data[fridt]); 
$SatDailyTotal=getDailyTotal($data[satst],$data[satot],$data[satdt]);
$SunDailyTotal=getDailyTotal($data[sunst],$data[sunt],$data[sundt]);

 $FinalTotal=getDailyTotal($stTotal,$otTotal,$dtTotal);



}



?>

<table width="600px;" border="1" class="TimeSet">

<tbody><tr>

<th><label>Days/Time<label></th>

<th><label>Mon <br/><span id="Setmon"></span></label></th>
    
 <th><label>Tue <br/><span id="Settue"></span></label></th>

<th><label>Wed <br/><span  id="Setwed"></span></label></th>

<th><label>Thu <br/> <span id="Setthu"></span></label></th>

<th><label>Fri <br/> <span id="Setfri"></span></label></th>

<th><label>Sat <br/> <span id="Setsat"></span></label></th>

<th><label>Sun <br/><span  id="Setsun"></span></label></th>

<th><label>Weekly Total</label></th>

</tr>

<tr>

<td style="width:100px;" nowrap="nowrap"><label>Straight Time</label></td>

<td><input type="text"  name="st1" autocomplete="off" id="st1" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" value="<?php if($data[monst]==".00"){ echo "0.00"; } else { echo $data[monst]; }?>" readonly="readonly" ></td>

<td><input type="text" name="st2" autocomplete="off" id="st2" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" value="<?php if($data[tuest]==".00"){ echo "0.00"; } else { echo $data[tuest]; }?>" readonly="readonly"  onkeyup="return timesheetcalci()" placeholder=""></td>

<td><input type="text"  name="st3" autocomplete="off" id="st3" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" value="<?php if($data[wedst]==".00"){ echo "0.00"; } else { echo $data[wedst]; }?>" readonly="readonly"  onkeyup="return timesheetcalci()"  placeholder=""></td>

<td><input type="text" name="st4"  autocomplete="off" id="st4" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  value="<?php if($data[thust]==".00"){ echo "0.00"; } else { echo $data[thust]; }?>"   readonly="readonly" onkeyup="return timesheetcalci()" placeholder=""></td>

<td><input type="text" name="st5" autocomplete="off" id="st5" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" value="<?php if($data[frist]==".00"){ echo "0.00"; } else { echo $data[frist]; }?>" readonly="readonly"   onkeyup="return timesheetcalci()"  placeholder=""></td>

<td><input type="text" name="st6" autocomplete="off" id="st6" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"   value="<?php if($data[satst]==".00"){ echo "0.00"; } else { echo $data[satst]; }?>" readonly="readonly" onkeyup="return timesheetcalci()" placeholder=""></td>

<td><input type="text" name="st7" autocomplete="off" id="st7" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"   value="<?php if($data[sunst]==".00"){ echo "0.00"; } else { echo $data[sunst]; }?>" readonly="readonly" onkeyup="return timesheetcalci()"   placeholder=""></td>




 <td align="right" ><span id="StTotal"><?php echo number_format((float)$stTotal, 2, '.', '')   ?></span></td>

</tr>

 <tr >

        <td style="width:100px;"><label>Overtime</label></td>

        <td><input type="text" autocomplete="off" name="ot1" id="ot1" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" value="<?php if($data[monot]==".00"){ echo "0.00"; } else { echo $data[monot]; }?>" readonly="readonly"   placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot2" id="ot2" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" value="<?php if($data[tueot]==".00"){ echo "0.00"; } else { echo $data[tueot]; }?>" readonly="readonly"   placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot3" id="ot3" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()"  value="<?php if($data[wedot]==".00"){ echo "0.00"; } else { echo $data[wedot]; }?>" readonly="readonly"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot4" id="ot4" style="text-align: right;"  onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()" value="<?php if($data[thuot]==".00"){ echo "0.00"; } else { echo $data[thuot]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot5" id="ot5" style="text-align: right;"  onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()"  value="<?php if($data[friot]==".00"){ echo "0.00"; } else { echo $data[friot]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot6" id="ot6" style="text-align: right;"  onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()" value="<?php if($data[satot]==".00"){ echo "0.00"; } else { echo $data[sat]; }?>" readonly="readonly" placeholder=""></td>

        
        <td><input type="text"  autocomplete="off" name="ot7" id="ot7" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  value="<?php if($data[sunot]==".00"){ echo "0.00"; } else { echo $data[sunot]; }?>" readonly="readonly" placeholder=""></td>


        <td align="right"><span id="OtTotal"><?php echo number_format((float)$otTotal, 2, '.', '')   ?></span></td>

    </tr>

 <tr>

        <td style="width:100px;"><label>Double Time</label></td>

        <td><input type="text" autocomplete="off" name="dt1" id="dt1" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()" value="<?php if($data[mondt]==".00"){ echo "0.00"; } else { echo $data[mondt]; }?>" readonly="readonly"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt2" id="dt2" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()" value="<?php if($data[tuedt]==".00"){ echo "0.00"; } else { echo $data[tuedt]; }?>" readonly="readonly"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt3" id="dt3" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()" value="<?php if($data[weddt]==".00"){ echo "0.00"; } else { echo $data[weddt]; }?>" readonly="readonly"   placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt4" id="dt4" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()" value="<?php if($data[thudt]==".00"){ echo "0.00"; } else { echo $data[thudt]; }?>" readonly="readonly"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt5" id="dt5" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()" value="<?php if($data[fridt]==".00"){ echo "0.00"; } else { echo $data[fridt]; }?>" readonly="readonly" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt6" id="dt6" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()"  value="<?php if($data[satdt]==".00"){ echo "0.00"; } else { echo $data[satdt]; }?>" readonly="readonly"  placeholder=""></td>      

        <td><input type="text" autocomplete="off" name="dt7" id="dt7"  style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()" value="<?php if($data[sundt]==".00"){ echo "0.00"; } else { echo $data[sundt]; }?>" readonly="readonly"  placeholder=""></td>

        <td align="right"><span id="DtTotal"><?php echo number_format((float)$dtTotal, 2, '.', '')   ?></span></td>

    </tr>
<tr style="background-color:#C8D7E6">

<td align="left"><label>Daily Total</label></td><td align="right"><span id="MonTotal" ><?php echo number_format((float)$MonDailyTotal, 2, '.', '')   ?></span></td><td align="right"><span id="TueTotal" ><?php echo number_format((float)$TueDailyTotal, 2, '.', '')   ?></span></td><td align="right"><span id="WedTotal" ><?php echo number_format((float)$WedDailyTotal, 2, '.', '')   ?></span></td><td align="right"><span id="ThuTotal" ><?php echo number_format((float)$ThuDailyTotal, 2, '.', '')   ?></span></td>
<td align="right"><span id="FriTotal" ><?php echo number_format((float)$FriDailyTotal, 2, '.', '')   ?></span></td><td align="right"><span id="SatTotal" ><?php echo number_format((float)$SatDailyTotal, 2, '.', '')   ?></span></td><td align="right"><span id="SunTotal" ><?php echo number_format((float)$SunDailyTotal, 2, '.', '')   ?></span></td>

          <!---<td colspan="8" align="right" style="padding:10px 0px 10px 0px; ">
                <label>Total All Hours</label>

           </td> -->
<input type="hidden" name="getTabVal" id="getTabVal" value="<?php echo $data[TimePortEntrystyle];?>"/>
            <td align="right">
                <b><span id="totals"><?php echo $FinalTotal;?></span></b>

            </td>


        </tr>
    


</tbody></table>
<?php
 
 if(date_format($data['ReviewedOn'], 'm/d/Y H:i A')!="")
 { $rev="Reviewed On:".date_format($data['ReviewedOn'], 'm/d/Y H:i A');
 }
 
if($data['submittedOn']!="")
{ 
 $abc= "Submitted On:".date_format($data['submittedOn'], 'm/d/Y H:i A')."&nbsp;&nbsp;".$rev;
}

?>
<input type="hidden" id="comment" value="<?php echo $data[comments];?>">
<input type="hidden" id="SubmitOndata" value="<?php echo $abc ;?>">


<?php
} else if($gettotalVal==1 && ($data[TimePortEntrystyle]=="" || $data[TimePortEntrystyle]=="0")){ 


if(date_format($data['ReviewedOn'], 'm/d/Y H:i A')!="")
 { $rev="Reviewed On:".date_format($data['ReviewedOn'], 'm/d/Y H:i A');
 }
 
if($data['submittedOn']!="")
{ 
 $abcd= "Submitted On:".date_format($data['submittedOn'], 'm/d/Y H:i A')."&nbsp;&nbsp;".$rev;
}
?>

 <table width="600px;" border="1" class="TimeSet">

<tbody><tr>

<th><label>Days/Time<label></th>

<th><label>Mon <br/><span id="Setmon"></span></label></th>
    
 <th><label>Tue <br/><span id="Settue"></span></label></th>

<th><label>Wed <br/><span  id="Setwed"></span></label></th>

<th><label>Thu <br/> <span id="Setthu"></span></label></th>

<th><label>Fri <br/> <span id="Setfri"></span></label></th>

<th><label>Sat <br/> <span id="Setsat"></span></label></th>

<th><label>Sun <br/><span  id="Setsun"></span></label></th>

<th><label>Weekly Total</label></th>

</tr>

<tr>

<td style="width:100px;" nowrap="nowrap"><label>Straight Time</label></td>

<td><input type="text"  name="st1" autocomplete="off" id="st1" style="text-align: right;" onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"   placeholder=""></td>

<td><input type="text" name="st2" autocomplete="off" id="st2" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  onkeyup="return timesheetcalci()" placeholder=""></td>

<td><input type="text"  name="st3" autocomplete="off" id="st3" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  onkeyup="return timesheetcalci()"  placeholder=""></td>

<td><input type="text" name="st4"  autocomplete="off" id="st4" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"   onkeyup="return timesheetcalci()" placeholder=""></td>

<td><input type="text" name="st5" autocomplete="off" id="st5" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  onkeyup="return timesheetcalci()"  placeholder=""></td>

<td><input type="text" name="st6" autocomplete="off" id="st6" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  onkeyup="return timesheetcalci()" placeholder=""></td>

<td><input type="text" name="st7" autocomplete="off" id="st7" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  onkeyup="return timesheetcalci()"   placeholder=""></td>




 <td align="right" ><span id="StTotal"></span></td>

</tr>

 <tr >

        <td style="width:100px;"><label>Overtime</label></td>

        <td><input type="text" autocomplete="off" name="ot1" id="ot1" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"   placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot2" id="ot2" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"   placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot3" id="ot3" style="text-align: right;" onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot4" id="ot4" style="text-align: right;"  onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot5" id="ot5" style="text-align: right;"  onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot6" id="ot6" style="text-align: right;"  onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()" placeholder=""></td>

        
        <td><input type="text"  autocomplete="off" name="ot7" id="ot7" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" placeholder=""></td>


        <td align="right"><span id="OtTotal"></span></td>

    </tr>

 <tr>

        <td style="width:100px;"><label>Double Time</label></td>

        <td><input type="text" autocomplete="off" name="dt1" id="dt1" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt2" id="dt2" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt3" id="dt3" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt4" id="dt4" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt5" id="dt5" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt6" id="dt6" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" placeholder=""></td>      

        <td><input type="text" autocomplete="off" name="dt7" id="dt7"  style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" placeholder=""></td>

        <td align="right"><span id="DtTotal"></span></td>

    </tr>
<tr style="background-color:#C8D7E6">

<td align="right"><label>Daily Total</label></td><td align="right"><span id="MonTotal" ></span></td><td align="right"><span id="TueTotal" ></span></td><td align="right"><span id="WedTotal" ></span></td><td align="right"><span id="ThuTotal" ></span></td><td align="right"><span id="FriTotal" ></span></td><td align="right"><span id="SatTotal" ></span></td><td align="right"><span id="SunTotal" ></span></td>

          

            <td align="right">
                <b><span id="totals"></span></b>

            </td>


        </tr>
    
<tr>

</tbody></table><input type="hidden" id="comment" value="<?php echo $data[comments];?>"><input type="hidden" id="SubmitOndata" value="<?php echo $abcd;?>">
<?php


}


else {  echo '<table width="600px;" border="1" class="TimeSet">

<tbody><tr>

<th><label>Days/Time<label></th>

<th><label>Mon <br/><span id="Setmon"></span></label></th>
    
 <th><label>Tue <br/><span id="Settue"></span></label></th>

<th><label>Wed <br/><span  id="Setwed"></span></label></th>

<th><label>Thu <br/> <span id="Setthu"></span></label></th>

<th><label>Fri <br/> <span id="Setfri"></span></label></th>

<th><label>Sat <br/> <span id="Setsat"></span></label></th>

<th><label>Sun <br/><span  id="Setsun"></span></label></th>

<th><label>Weekly Total</label></th>

</tr>

<tr>

<td style="width:100px;" nowrap="nowrap"><label>Straight Time</label></td>

<td><input type="text"  name="st1" autocomplete="off" id="st1" style="text-align: right;" onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"   placeholder=""></td>

<td><input type="text" name="st2" autocomplete="off" id="st2" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  onkeyup="return timesheetcalci()" placeholder=""></td>

<td><input type="text"  name="st3" autocomplete="off" id="st3" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  onkeyup="return timesheetcalci()"  placeholder=""></td>

<td><input type="text" name="st4"  autocomplete="off" id="st4" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"   onkeyup="return timesheetcalci()" placeholder=""></td>

<td><input type="text" name="st5" autocomplete="off" id="st5" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  onkeyup="return timesheetcalci()"  placeholder=""></td>

<td><input type="text" name="st6" autocomplete="off" id="st6" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  onkeyup="return timesheetcalci()" placeholder=""></td>

<td><input type="text" name="st7" autocomplete="off" id="st7" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"  onkeyup="return timesheetcalci()"   placeholder=""></td>




 <td align="right" ><span id="StTotal"></span></td>

</tr>

 <tr >

        <td style="width:100px;"><label>Overtime</label></td>

        <td><input type="text" autocomplete="off" name="ot1" id="ot1" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"   placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot2" id="ot2" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()"   placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot3" id="ot3" style="text-align: right;" onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot4" id="ot4" style="text-align: right;"  onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot5" id="ot5" style="text-align: right;"  onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="ot6" id="ot6" style="text-align: right;"  onkeypress="return isNumber(event)"   onkeyup="return timesheetcalci()" placeholder=""></td>

        
        <td><input type="text"  autocomplete="off" name="ot7" id="ot7" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" placeholder=""></td>


        <td align="right"><span id="OtTotal"></span></td>

    </tr>

 <tr>

        <td style="width:100px;"><label>Double Time</label></td>

        <td><input type="text" autocomplete="off" name="dt1" id="dt1" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt2" id="dt2" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt3" id="dt3" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt4" id="dt4" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()"  placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt5" id="dt5" style="text-align: right;"  onkeypress="return isNumber(event)" onkeyup="return timesheetcalci()" placeholder=""></td>

        <td><input type="text" autocomplete="off" name="dt6" id="dt6" style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" placeholder=""></td>      

        <td><input type="text" autocomplete="off" name="dt7" id="dt7"  style="text-align: right;"  onkeypress="return isNumber(event)"  onkeyup="return timesheetcalci()" placeholder=""></td>

        <td align="right"><span id="DtTotal"></span></td>

    </tr>
<tr style="background-color:#C8D7E6">

<td align="right"><label>Daily Total</label></td><td align="right"><span id="MonTotal" ></span></td><td align="right"><span id="TueTotal" ></span></td><td align="right"><span id="WedTotal" ></span></td><td align="right"><span id="ThuTotal" ></span></td><td align="right"><span id="FriTotal" ></span></td><td align="right"><span id="SatTotal" ></span></td><td align="right"><span id="SunTotal" ></span></td>

          

            <td align="right">
                <b><span id="totals"></span></b>

            </td>


        </tr>
    
<tr>

</tbody></table><input type="hidden" id="comment" value=""><input type="hidden" id="SubmitOndata" value="">' ; 


}
sqlsrv_free_stmt($result);
?>
