<?php

try
{
    function getKeyValue($key,$fileName='')
    {	
		if($fileName == ".config")
		{
			return "";
		}
		else
		{
			$pathOfXML = "uploadConfig/".$fileName;
			if (file_exists($pathOfXML)) 
			{	 
			 $sxe = simplexml_load_file($pathOfXML);
			 foreach($sxe->xpath('/configuration/appSettings/add') as $item) {
					if ($item['key'] ==$key){ return $item['value']; }
			 }
			}		
		}
	}
	
// function getAllKeyValue($fileName='')
    // {	
		// $allKeys = array();
		// $allValues = array();
		
		// if($fileName == ".config")
		// {
			// return "";
		// }
		// else
		// {
			// $pathOfXML = "uploadConfig/".$fileName;			
			// if (file_exists($pathOfXML)) 
			// {	 
			 // $sxe = simplexml_load_file($pathOfXML);
			 // foreach($sxe->xpath('/configuration/appSettings/AllowedExtension/add') as $item) 
			 // {						
				// $k.=$item['key'].",";
				// $v.=$item['value'].",";				
			 // }
			// }		
		// }		
		// $allKeys = explode(",", $k);
		// $allValues = explode(",", $v);				
		// $combine = array_combine($allKeys,$allValues);
		// return $combine;
	// }														
	
}
catch(Exception $e)
{
  echo 'Message: ' .$e->getMessage();
}
?>