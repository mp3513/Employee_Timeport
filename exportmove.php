<?php 
session_start();
$DB_Server = "10.0.0.21\mssqlserver2008"; // MySQL Server
$DB_Username = "sa"; // MySQL Username
$DB_Password = "Rayban007"; // MySQL Password
$DB_DBName = "IRM_Stage5March"; // MySQL Database Name
$DB_TBLName = "Customer"; // MySQL Table Name
$xls_filename = 'export_'.date('Y-m-d').'.xls'; // Define Excel (.xls) file name



 
/***** DO NOT EDIT BELOW LINES *****/
// Create MySQL connection
$sql = "Select * from $DB_TBLName";
$Connect = @sqlsrv_connect($DB_Server, $DB_Username, $DB_Password) or die("Failed to connect to sqlsrver:<br />" . sqlsrv_error() . "<br />" . mysql_errno());
// Select database
$Db = @sqlsrv_select_db($DB_DBName, $Connect) or die("Failed to select database:<br />" . sqlsrv_error(). "<br />" . sqlsrv_errno());
// Execute query
$result = @sqlsrv_query($sql,$Connect) or die("Failed to execute query:<br />" . sqlsrv_error(). "<br />" . sqlsrv_errno());
 echo count($result); die;
// Header info settings
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$xls_filename");
header("Pragma: no-cache");
header("Expires: 0");
 
/***** Start of Formatting for Excel *****/
// Define separator (defines columns in excel &amp; tabs in word)
$sep = "\t"; // tabbed character
 
// Start of printing column names as names of MySQL fields
for ($i = 0; $i<mysql_num_fields($result); $i++) {
  echo mysql_field_name($result, $i) . "\t";
}
print("\n");
// End of printing column names
 
// Start while loop to get data
while($row = mysql_fetch_row($result))
{
  $schema_insert = "";
  for($j=0; $j<mysql_num_fields($result); $j++)
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