<?php

error_reporting(E_ALL);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once("$root/db/db_connection.php");
require_once("$root/webapps/mpdf/mpdf.php");

$sql = "SELECT css, html, orientation FROM ep_pdf_templates
        WHERE pdf_id = :id;";

$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $_GET['reportId'], PDO::PARAM_INT);
$stmt->execute();

$pdf = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM roi_stored_values
        WHERE roi_id = ?
        ORDER BY stored_dt DESC";

$stmt = $db->prepare($sql);	
$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
$stmt->execute();
$stored_values = $stmt->fetchall();

$sql = "SELECT * FROM ep_created_rois
        WHERE roi_id = ? LIMIT 1";

$stmt = $db->prepare($sql);	
$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
$stmt->execute();
$roi = $stmt->fetch();        

$sql = "SELECT * FROM roi_users
        WHERE user_id = (
                SELECT user_id FROM ep_created_rois
                WHERE roi_id = ?
        ) LIMIT 1";

$stmt = $db->prepare($sql);	
$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(); 

$current_values = json_decode($stored_values[0]['value_array'], true);

$pdfhtml = $pdf['html'];

foreach($current_values as $value){
        $pdfhtml = str_replace("<formatted>" . $value['address'] . "</formatted>", $value['formattedValue'], $pdfhtml);
}

$pdfhtml = str_replace('<tag>roi_id</tag>', $_GET['roi'], $pdfhtml);
$pdfhtml = str_replace('<tag>Companyname</tag>', $roi['roi_title'], $pdfhtml);
$pdfhtml = str_replace('<tag>DatePrepared</tag>',  date("F j, Y"), $pdfhtml);
$pdfhtml = str_replace('<tag>Preparedby</tag>',  $user['first_name'] . ' ' . $user['last_name'], $pdfhtml);
$pdfhtml = str_replace('<tag>Email</tag>',  $user['username'], $pdfhtml);
$pdfhtml = str_replace('<tag>Phone</tag>',  $user['phone'], $pdfhtml);
$pdfhtml = str_replace('<tag>LinktoCalculator</tag>', '<a href="' . $_GET['roiPath'] . '">Link to the ROI</a>', $pdfhtml);
$pdfhtml = str_replace('<tag>RoiPath</tag>', $_GET['roiPath'], $pdfhtml);

$report = '<html><head>' . $pdf['css'] . '</head><body class="pdfbody">' . $pdfhtml . '</body></html>';

$stylesheet = file_get_contents("$root/webapps/assets/css/pdfstyle.css");
$comp_stylesheet = file_get_contents("$root/webapps/assets/css/style.css");

$mpdf->showImageErrors = true;

$mpdf = new mPDF('s', $pdf['orientation']);

// $mpdf->WriteHTML($stylesheet,1);
// $mpdf->WriteHTML($comp_stylesheet,1);
    
$mpdf->WriteHTML($report);

$mpdf->Output("$root/webapps/assets/customwb/10016/pdf/" . $roi['roi_title'] . ".pdf",'F');