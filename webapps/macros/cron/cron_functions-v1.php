<?php

class MacroFunctions
{

	public $response = array(); 

	//Create database object
	private $_db;
		
	/**
	 * Checks for a database object and creates one if none is found
	 **/
	public function __construct($db=NULL)
	{
		if(is_object($db))
		{
			$this->_db = $db;
		}
		else
		{
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
		}
	}
	




function sendEmail($recipient,$ccrecipient,$bccrecipient,$emailsubject,$emailfrom,$body,$attachment=NULL,$attachmentname=NULL){
	

	  try{
	 
		  if (!filter_var($recipient, FILTER_VALIDATE_EMAIL) === false) {
		
		  //Create the Transport
		  $transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
		
		  //Create the Mailer using your created Transport
		  $mailer = Swift_Mailer::newInstance($transport);
		 
		  //Create the message
		  $message = Swift_Message::newInstance()
		  ->setSubject($emailsubject)
		  ->setFrom(array($emailfrom . '@theroishop.com'))
		  ->setTo(array($recipient))
		  ->setBody($body, 'text/html');

		  if ($ccrecipient) {$message->setCc(array($ccrecipient));}
		  if ($bccrecipient) {$message->setBcc(array($bccrecipient));}
		  if ($attachment && $attachmentname){$message->attach(Swift_Attachment::fromPath($attachment)->setFilename($attachmentname));} else {
		  if ($attachment) {$message->attach(Swift_Attachment::fromPath($attachment));}}

		  $mailer->send($message, $failures);	
		  
		  $this->response['success']='success';
		  $this->response['sentTo']=$recipient;
		  $this->response['sentCC']=$ccrecipient;
		  $this->response['sentBCC']=$bccrecipient;
		  $this->response['timeSent']=date('Y-m-d H:i:s');
		  
		  $this->response['failures'] = $failures;
		  }
		  }catch(Exception $e){
		  
		  $this->response['success']='failed';
		  
		  }finally {
		  	
		  }
	
	return json_encode($this->response);
	
}


function createPDF($instanceID,$reportID,$reportName){

	
	global $formatter;
	
	$g = new GeneralFunctions();
	$wbroiID = $g->GetWbroiIDFromInstanceID($instanceID);
	//Get the Report to be PDFed
	//retreive the css and html of the pdf report
	$reportCSS 		= $g->Dlookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	$reportHTML 	= $g->Dlookup('html','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	
	
	
	
	//Get a list of the instance values for this instance
		 $SQL = "SELECT * 
	    		FROM `wb_roi_instance_values`
	    		WHERE instanceID=$instanceID;";
	    
		$list = $g->returnarray($SQL);
	
		$numrows = count($list);
		$x=0;
		if($numrows>0){
		  foreach($list as $r){
			$x = $x + 1;	
			$reportHTML 		= str_replace('<tag>' . $r['field'] . '</tag>', $r['formatted_value'], $reportHTML);   
		  }
		}
		
		
	//Need to loop through the standard values table and replace accodingly
		 $SQL = "SELECT * 
	    		FROM `wb_roi_instance_values_standard`
	    		WHERE instanceID=$instanceID;";
		
		//echo $SQL;
		$list = $g->returnarray($SQL);
	
		$numrows = count($list);
		$x=0;
		if($numrows>0){
		  foreach($list as $r){
			$x = $x + 1;	
			$reportHTML = str_replace('<tagstd>' . $r['stdfieldID'] . '</tagstd>', $r['value'], $reportHTML);
		  }
		}
		
		//Need to loop through the fields that are used in this report and replace accordingly
		 $SQL = "SELECT * 
	    		FROM `wb_roi_reports_fieldusage` t1
	    		JOIN `wb_roi_instance_values` t2 ON t1.fieldID=t2.field
	    		WHERE t1.reportID=$reportID AND t2.instanceID=$instanceID;";
		
		//echo $SQL;
		$list = $g->returnarray($SQL);
	
		$numrows = count($list);
		$x=0;
		if($numrows>0){
		  foreach($list as $r){
			$x = $x + 1;	
			$reportHTML = str_replace('<tagrpt>' . $r['usageID'] . '</tagrpt>', $formatter->format($r['value'],$r['numeralFormat']) , $reportHTML);
		  }
		}	

		
		
	//Finalize the report to be pdfed
		$report = '<html><head>' . $reportCSS . '</head><body>' . $reportHTML . '</body></html>';
			
		$stylesheet = file_get_contents('../../assets/css/pdfstyle.css');
		$comp_stylesheet = file_get_contents('../../assets/css/style.css');
		
		//Get the orientation of the page
		$orient = $g->DLookup('PDForientation','wb_roi_reports','wb_roi_report_ID=' . $pdfreportID);
		switch ($orient) {
		    case 0:
		        $page = 'A4';
		        break;
		    case 1:
		        $page = 'A4-L';
		        break;
		    
		    default:
		        $page = 'A4';
		}
		//Get the name of the file that should be created
		$filename = $g->DLookup('filename','wb_roi_reports','wb_roi_report_ID=' . $pdfreportID);		
		
		try {
		
		$mpdf = new mPDF('c', $page);
			
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($comp_stylesheet,1);
			
		$mpdf->WriteHTML($report);
		
		$mpdf->Output('../../assets/customwb/' . $wbroiID . '/pdf/' . $wbroiID . '-' . $instanceID . '.pdf','F');
		$mpdf->Output('../../assets/customwb/' . $wbroiID . '/pdf/' . $filename . '.pdf','F');

		$this->response['success'] = 'success';
		$this->response['filename'] = $wbroiID . '-' . $instanceID . '.pdf';
		$this->response['fullpath'] = 'http://www.theroishop.com/webapps/assets/customwb' . $wbroiID . '/pdf/' . $wbroiID . '-' . $instanceID . '.pdf';
		  }
		  catch(Exception $e){
		  
		  $this->response['success']='failed';
		  
		  
		  }finally {
		  	
		  }

		return json_encode($this->response);

	 //*/ 
}










 





























}

?>