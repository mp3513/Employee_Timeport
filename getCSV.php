<?php 
session_start();
require_once("includes/db_connection_irm.php");
require_once("includes/functions.php");
$iconn 		 = getIRMDatabaseConnection();


	$filename = 'example.csv';
    $csv_terminated = "\n";
    $csv_separator = ",";
    $csv_enclosed = '"';
    $csv_escaped = "\\";	
	
	
	
     $sql_query = "WITH tblAcroPortRecord_Procedure_csv AS ( SELECT ct.first_name as Temporary ,'' as 'SS#',
	 convert(varchar(25),weekendingdate,120) as 'Weekending',	
	convert(varchar(25),ap.submittedOn,120) as 'Date','' as 'Pay Type Id','' as 'Time Worked',cu.customer_id as 'Client#','' as AssignmentID
	
	FROM tblPayrollAdministrator as pa left outer join customer as cu ON cu.PayrollAdministrator =pa.row_id left outer join EmployeePlacement as ep ON ep.client_fk = cu.customer_id FULL OUTER JOIN tblAcroPortRecords as ap ON ep.row_id=ap.empPlacementId LEFT 
	OUTER JOIN Candidate as ct ON ep.employee_id = ct.row_id where cu.ManualTimesheets=1 and ep.isTermStatusViewedByAcroPayroll =0 and (ap.rowId is null or ap.status ='S') and (ap.status='S') and pa.row_id=2 ) SELECT * FROM tblAcroPortRecord_Procedure_csv";  //edit here
    // Gets the data from the database
     	$result = sqlsrv_query($iconn, $sql_query , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )) or die("ERROR Query");		
 	$fields_cnt = sqlsrv_num_fields($result); 
  
    $schema_insert = '';
	
	
	for ($i = 0; $i < $fields_cnt; $i++)
    {
	 $GetField = sqlsrv_field_metadata($result);  
     $arr=$GetField[$i]; 	
     $finallname=$arr[Name]; 
    $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
         stripslashes($finallname)) . $csv_enclosed; 
        $schema_insert .= $l;
        $schema_insert .= $csv_separator;
   
	}
	
	
	

	// end for 
    $out = trim(substr($schema_insert, 0, -1));
    $out .= $csv_terminated;
    // Format the data
    while ($row = sqlsrv_fetch_array($result))
    {
	
        $schema_insert = '';
		
        for ($j = 0; $j < $fields_cnt; $j++)
        {
		
            if ($row[$j] == '0' || $row[$j] != '')
            {
                if ($csv_enclosed == '')
                {
                    $schema_insert .= $row[$j];
                } else
                {
                    $schema_insert .= $csv_enclosed .
                    str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $row[$j]) . $csv_enclosed;
                }
            } else
            {
                $schema_insert .= '';
            }
            if ($j < $fields_cnt - 1)
            {
                $schema_insert .= $csv_separator;
            }
        } // end for
         $out .= $schema_insert; 
        $out .= $csv_terminated;
    } // end while
	
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: " . strlen($out));
    // Output to browser with appropriate mime type, you choose ;)
    header("Content-type: text/x-csv");
    //header("Content-type: text/csv");
    //header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=$filename"); 
    echo $out;
    exit;
	

?>

