<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database

$fieldID 		= $_POST['fieldid'];
$startdatestng  = $_POST['starting'];
$currentdate    = $_POST['ending'];

$wherestrng = "field=$fieldID 
			AND dateCreated BETWEEN '" . $startdatestng . "' AND '" . $currentdate . "'";

if (isset($_POST["histomax"])) {
    $histomax		= $_POST['histomax'];   
}else{  
    $histomax		= $g->DMax('value','wb_roi_instance_values',$wherestrng);
}

if (isset($_POST["histomin"])) {
    $histomin		= $_POST['histomin'];   
}else{  
    $histomin		= $g->DMin('value','wb_roi_instance_values',$wherestrng);
}


$histodif = $histomax - $histomin;

$histointerval = $histodif / 10;

$histo0 = $histomin;
$histo1 = $histo0 + (1 * $histointerval);
$histo2 = $histo0 + (2 * $histointerval);
$histo3 = $histo0 + (3 * $histointerval);
$histo4 = $histo0 + (4 * $histointerval);
$histo5 = $histo0 + (5 * $histointerval);
$histo6 = $histo0 + (6 * $histointerval);
$histo7 = $histo0 + (7 * $histointerval);
$histo8 = $histo0 + (8 * $histointerval);
$histo9 = $histo0 + (9 * $histointerval);
$histo10 = $histo0 + (10 * $histointerval);




$SQL = "select 
	    case 
	        when value between $histo0 and $histo1 then '00-$histo0-$histo1' 
	        when value between $histo1 and $histo2 then '01-$histo1-$histo2'
	        when value between $histo2 and $histo3 then '02-$histo2-$histo3'
	        when value between $histo3 and $histo4 then '03-$histo3-$histo4'
	        when value between $histo4 and $histo5 then '04-$histo4-$histo5'
	        when value between $histo5 and $histo6 then '05-$histo5-$histo6'
	        when value between $histo6 and $histo7 then '06-$histo6-$histo7'
	        when value between $histo7 and $histo8 then '07-$histo7-$histo8'
	        when value between $histo8 and $histo9 then '08-$histo8-$histo9'
	        when value between $histo9 and $histo10 then '09-$histo9-$histo10'
	        ELSE '10-Not Given'
	    end as bucket, 
	    
	    count(*) AS COUNT
	from wb_roi_instance_values
	WHERE field=$fieldID 
				AND dateCreated BETWEEN '" . $startdatestng . "' AND '" . $currentdate . "'
	group by 1"; 
	
	$results = $g->returnarray($SQL);
	
	$numrows = count($results);
    $x = 0;

	$allarray = array();
	
	if($numrows>0){
		foreach($results as $r){

			$allarray[$x]['name'] = substr($r['bucket'],3) . ' ';
			$allarray[$x]['y'] = $r['COUNT'];
			$x = $x + 1;
			}
	}
 	

	echo json_encode($allarray, JSON_NUMERIC_CHECK);
	
	
?>