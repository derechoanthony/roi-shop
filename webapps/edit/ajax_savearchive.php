
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database

$reportID 		= $_POST['reportid'];
$listonly		= $_POST['listonly'];
$css			= $_POST['csshtml'];
$html			= $_POST['html'];
$script			= $_POST['script'];
$archivename	= $_POST['archivename'];
$archivedesc	= $_POST['archivedesc'];

if($listonly==0){
$g->InsertArchive($reportID, $archivename, $archivedesc, $html, $css, $script);
}
//echo 'done';

//Get new list of items

//Get all of the archives for this report
	$SQL = "SELECT *
	FROM wb_roi_reports_archives t1
	WHERE wb_roi_report_ID=$reportID
	ORDER BY t1.dateCreated DESC, t1.archiveName ASC;";
	
	$list = $g->returnarray($SQL);
    $archiveitems 	= '';
	
	$numrows = count($list);
    $x = 0;
	
						
	if($numrows>0){
		$archiveitems	= $archiveitems . '<li class="divider"></li>';
		foreach($list as $r){
			$x = $x + 1;
			$archiveitems	= $archiveitems . '<li><a class="getarchive" data-archiveid="' . $r['archiveID'] . '" >' . $r['archiveName'] . ' (' . $g->shortdate($r['dateCreated']) . ')</a></li>';
			}
		//$archiveitems	= $archiveitems . '</div>';
	}

	$archivelist = '
                    <li><a data-toggle="modal" href="#modal-savearchive">Save to Archive</a></li>
                    ' . $archiveitems . '
                ';
	echo $archivelist;

?>

