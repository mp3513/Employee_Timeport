<?php
session_start();
require_once("includes/db_connection_irm.php");
require_once("includes/functions.php");
require_once("ConfigurationRead.php");
require_once("includes/headerApprover.php");


$iconn 		 = getIRMDatabaseConnection();





$sql="WITH tblAcroPortRecord_Procedure_csv AS ( SELECT ep.row_id,ep.termination_date,ep.isTermStatusViewedByAcroPayroll, 
ap.*, CASE ap.status WHEN 'S' THEN 'Submitted' WHEN 'V' THEN 'Viewed By Acro'
 END as [ap.status], CASE WHEN isnull(ep.termination_date,'')='' then 'Active' else 'Terminated' END as [e.empstatus], 
 ct.first_name, ct.last_name,ct.ssn,cu.AccCustomerID as AsignmentId,cu.customer_name , ROW_NUMBER() OVER (ORDER BY ep.row_id DESC ) AS 'RowNumber'
  FROM tblPayrollAdministrator as pa left outer join customer as cu ON cu.PayrollAdministrator =pa.row_id
   left outer join EmployeePlacement as ep ON ep.client_fk = cu.customer_id FULL OUTER JOIN tblAcroPortRecords as ap
    ON ep.row_id=ap.empPlacementId LEFT OUTER JOIN Candidate as ct ON ep.employee_id = ct.row_id where 
    cu.ManualTimesheets=1 and ep.isTermStatusViewedByAcroPayroll =0 and (ap.rowId is null or ap.status ='S') 
    and (ap.status='S')  and  ap.TimePortEntrystyle !=0  and pa.row_id=2 ) SELECT * FROM tblAcroPortRecord_Procedure_csv";
$result = sqlsrv_query($iconn, $sql , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) or die("ERROR Query");		
  	$fields_cnt = sqlsrv_num_fields($result);  


?>






			
			<?php
		
			$timestamp = date("m/d/Y_H:i");
	$filename = $file."_".$fileStatus."_Records_".$timestamp;
	
	header("Content-type: application/vnd.ms-excel");
	//header("Content-disposition: csv" . date("Y-m-d") . ".csv");
	header("Content-disposition: filename=".$filename.".csv");
			
    $csv_output="";
	$csv_output.="Employee Name,SS#,Weekending,date,PayTypeId,Time Worked,Client#,AssignmentId";
	$csv_output.= "\n";
	$resultSetCSV = sqlsrv_query( $iconn, $queryReportCSV, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$countRow = sqlsrv_num_rows($resultSetCSV);
	print $csv_output;
	$csv_output = "";

	
	
	
		// die;
    
    // Show name of column 3
    
		
			while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
			{	
			
		//	print_r($row); 
			 $arr=array_keys($row);			 
	      	$GetDates=date_format($row['weekEndingDate'], 'm/d/Y');							
			 $Getmonth=explode('/',$GetDates);
			$month=$Getmonth[0];
			$date=$Getmonth[1];
			$year=$Getmonth[2]; 
			
			$days='7'; $format = 'd/m/Y'; 		
			$dateArray = array();
			for($i=0; $i<=$days-1; $i++){
			$dateArray[] = '"'.date($format, mktime(0,0,0,$month,($date-$i),$year)).'"'; 	}
			$abc= array_reverse($dateArray); 		 
		   			
			for($j=14;$j<=34; $j++)
			{	
			 $val=$arr[$j];
			$getFieldVal=$row[$val];			
		
			
		if($j==14 || $j==21 || $j==28)
			{
			$FinalDate=$abc[0];
			} 
			 if($j==15 || $j==22 || $j==29)
			{
			$FinalDate=$abc[1];
			} 
			 if($j==16 || $j==23 || $j==30)
			{
			$FinalDate=$abc[2];
			}
			
			 if($j==17 || $j==24 || $j==31)
			{
			$FinalDate=$abc[3];
			}
			
			 if($j==18 || $j==25 || $j==32)
			{
			$FinalDate=$abc[4];
			}
			
			 if($j==19 || $j==26 || $j==33)
			{
			$FinalDate=$abc[5];
			}
			
			
					
			 if($j==20 || $j==27 || $j==34)
			{
			 $FinalDate=$abc[6];
			}
			
						
		 	

			if($j==14 && $j<=20)
			{			
			$payTypeId="REG";
				
			}
			else if($j>20 && $j<=27)
			{
				$payTypeId="OT";
			}
			else if($j>27 && $j<=34)
			{
				$payTypeId="DT";
				
			}
					

		    
					
			if($getFieldVal!=".00")			
			{			
		$employeeName =  $row['last_name']." ".$row['first_name'];
		$csv_output .='"'.normalize_str($employeeName).'"'.",";
		//$csv_output .='"'.normalize_str($row['customer_name']).'"'.",";	
		$csv_output .='"44444"'.",";	
		$csv_output .='"'.date_format($row['weekEndingDate'], 'm/d/Y').'"'.",";
		
		$csv_output .='"'.normalize_str($FinalDate).'"'.",";		
		//$csv_output .='"'.normalize_str($status).'"'.",";		
		$csv_output .='"'.normalize_str($payTypeId).'"'.",";
		$csv_output .='"'.normalize_str($getFieldVal).'"'.",";
	    $csv_output .='"'.normalize_str($row['row_id']).'"'.",";
	//	$csv_output .='"'.normalize_str($row['timeSheetDocName']).'"'.",";		
		
		$csv_output .='"'.normalize_str($row['AsignmentId']).'"'."";
		
		$csv_output .="\n";
		print $csv_output;
		$csv_output="";
		}

				  } 
				  
				
				 }
				  ?>
				
			
			
