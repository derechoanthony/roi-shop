
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database

$aurgid			= $_POST['aurgid'];
$newval			= $_POST['newval'];


$g->UpdateMacroAurgValue($aurgid, $newval);

//echo 'done';

//Get new list of items


?>

