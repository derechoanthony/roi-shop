
<?php 

//This file adds the tab navigation to the details page 'details.php'
//To add a new tab, just add it to the array.


$tabs 		= array("Preview","Edit","Share","Details","Dashboard");
$files 		= array("preview","edit","share","details","dashboard");
$tabline 	= '<p>  ';

$wbappID	= $_GET['wbappID'];
$key 		= $_GET['key'];

for($x = 0; $x < count($tabs); $x++) {
	$classcolor = ($files[$x]==$tabname ? 'primary' : 'default');
	$tabline = $tabline . '<a class="btn btn-' . $classcolor . '" href="../' . $files[$x] . '/?wbappID=' . $wbappID . '&key=' . $key . '"> ' . $tabs[$x] . '</a>  ';	
}

	$tabline = $tabline . '  </p>';
	
	//echo $tabline;

?>


    
    
    
    