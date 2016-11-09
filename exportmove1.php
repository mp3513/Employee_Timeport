
<?php 
session_start();
require_once("includes/db_connection_irm.php");
require_once("includes/functions.php");
require_once("ConfigurationRead.php");
$iconn =getIRMDatabaseConnection();

$serverName = "10.0.0.21\mssqlserver2008"; 
		$dbName		= "IRM_Stage5March";
		$userName	= "sa";
		$password	= "Rayban007";
		
$connectionInfo = array("UID"=>$userName, "PWD"=>$password, "Database"=>$dbName);
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false )
{
     echo "Could not connect.\n";
     die( print_r( sqlsrv_errors(), true));
}

/* Set up and execute the query. */
$sql = "SELECT * FROM country";
$result = sqlsrv_query( $conn, $sql);
 
/* header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=abcd.xls");
header("Pragma: no-cache");
header("Expires: 0"); */
 
/***** Start of Formatting for Excel *****/
// Define separator (defines columns in excel &amp; tabs in word)
$sep = "\t"; // tabbed character
 
// Start of printing column names as names of MySQL fields
/* for ($i = 0; $i<sqlsrv_num_fields($result); $i++) {
  echo sqlsrv_get_field($result, $i) . "\t";
} */
$i=0;
   while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
   {
	 $docid = sqlsrv_field_metadata($result);  
     $arr=$docid[$i]; 
	// print_r($arr);
    echo  $finallname=$arr[Name]; 
	
	$i++;
	}



print("\n");
// End of printing column names
 
// Start while loop to get data
while($row = sqlsrv_fetch_array($result))
{
  $schema_insert = "";
  for($j=0; $j<sqlsrv_num_fields($result); $j++)
  {
    if(!isset($row[$j])) {
      $schema_insert .= "NULL".$sep;
    }
    elseif ($row[$j] != "") {
      $schema_insert .= "$row[$j]".$sep;
    }
    else {
      $schema_insert .= "".$sep;
    }
  }
  $schema_insert = str_replace($sep."$", "", $schema_insert);
  $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
  $schema_insert .= "\t";
  print(trim($schema_insert));
  print "\n";
}

	?>

