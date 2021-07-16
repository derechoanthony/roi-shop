
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/sandwebapp/core/init.php" ); 									// Sets up connection to database

$reportID 	= $_POST['reportid'];
$csshtml	= $_POST['csshtml'];
$html		= $_POST['html'];
$script		= $_POST['script'];

//echo $csshtml;
$g->DUpdatecss($csshtml, $reportID);

$g->DUpdatehtml($html, $reportID);
$g->DUpdatescript($script, $reportID);


?>

