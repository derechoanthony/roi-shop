<?php
    
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once("$root/vendor/autoload.php");
    require "$root/webapps/core/functions/general.php";
    require "$root/webapps/core/functions/weblets.php";
    
    $g = new GeneralFunctions($db);

	$reportID 	= $_POST['reportId'];
    
    $reportCSS 	= $g->Dlookup('css','ep_pdf_templates','pdf_template=' . $reportID);
    $reportHTML = $g->Dlookup('html','ep_pdf_templates','pdf_template=' . $reportID);
    
    $sql = "SELECT * FROM roi_stored_values
            WHERE roi_id = ?";
				
	$stmt = $db->prepare($sql);	
	$stmt->bindParam(1, $_POST['roi'], PDO::PARAM_INT);
	$stmt->execute();
    $stored_values = $stmt->fetchall();
    
    $current_values = json_decode($stored_values[0]['value_array'], true);

    $sql = "SELECT * FROM hidden_entities
            WHERE type = 'section' AND roi = ?";

    $stmt = $db->prepare($sql);	
    $stmt->bindParam(1, $_POST['roi'], PDO::PARAM_INT);
    $stmt->execute();
    $hidden_sections = $stmt->fetchall();
	
    $report = '<html><head>' . $reportCSS . '</head><body class="pdfbody">' . $reportHTML . '</body></html>';

    foreach($current_values as $value){
        $report = str_replace("<formatted>" . $value['address'] . "</formatted>", $value['formattedValue'], $report);
    }

    foreach($hidden_sections as $hidden){
        $report = str_replace("<section" . $hidden['entity_id'] . ">", 'style="display:none;"', $report);
    }
        
    try{
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4-L',
            'mode' => 's'
        ]);

        $mpdf->WriteHTML(utf8_encode($report));
        $mpdf->Output('filename.pdf');
    } catch(\Mpdf\MpdfException $e){
        echo $e->getMessage();
    }

    exit;