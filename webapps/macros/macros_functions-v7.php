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
	

function OpenModal($macroID, $instanceID, array $aurgs){
	
	$subresponse = array();
	
	$g1 = new GeneralFunctions();
	//$outputs = array();
	//extract variables
	
	
	//aurguments list
	//0 - elementID
	//1 - reportID of modal
	$wbroiID = $g1->GetWbroiIDFromInstanceID($instanceID);
	$macrostatus = $g1->DLookup('macrostatus','wb_roi_reports_macros','usedmacroID=' . $macroID);
	if($macrostatus==0) {$this->subresponse['errorlog']=1;}
	$modal = $g1->DLookup('HTML','wb_roi_reports','wb_roi_report_ID=' . $aurgs[16]);
	
	if ($aurgs[18]=='1') {$modalsizeclass='';}
	if ($aurgs[18]=='2') {$modalsizeclass='modal-lg';}
	if ($aurgs[18]=='3') {$modalsizeclass='modal-sm';}
	
	$modal = '<div id="macromodal" class="modal fade" aria-hidden="true">
                  <div class="modal-dialog ' . $modalsizeclass . '">
                  <div class="modal-content">
                  <div class="modal-body">
                    <div id="modalcontent">' . $modal . '</div>
                  </div>
                  </div>
                  </div>
                  </div>';
	
	$this->subresponse['nextaction']=3; 	//3=Open Modal Window
		$this->subresponse['macroID'] = $macroID;
		$this->subresponse['instanceID'] = $instanceID;
		$this->subresponse['macroaurgs'] = $aurgs;
	$this->subresponse['modalreport'] = $aurgs[16];
	
	
	$this->subresponse['modalsize'] = $modalsizeclass;
	$jsonresponse = json_encode($this->subresponse);
	$g1->InsertMacroResponse($wbroiID, $instanceID, $macroID, $jsonresponse);
	
	$this->subresponse['modal'] = $modal;
	
	return json_encode($this->subresponse);
}

function CloseModal(){
	$subresponse = array();
	$this->subresponse['nextaction']=4;
	$this->subresponse['modalid'] = 'macromodal';
	return json_encode($this->subresponse);
}


function PushLeadToMarketo($instanceID,$mkConnectionID){

	$subresponse = array();
	$g = new GeneralFunctions();
	
	$connection = $g->getMarketoConnection($mkConnectionID);
	$mapping 	= $g->getMarketoMapping($instanceID,$mkConnectionID);
	
	$lead1 = array();
	$errorsum = 0;
	foreach($mapping as $r){
		$lead1[$r['mkfieldName']] = $r['selectedvalue'];
		if($r['required']==1)
		{
			if($r['selectedvalue'] == NULL || $r['selectedvalue'] == ''){$errorsum = $errorsum/1 + 1;}	
		}
	}
	
	if($errorsum == 0){
	
	$p = new PushLeads();
	$hostname = "https://" . $connection['mkHost'] . ".mktorest.com";
	$p->host 			= $hostname;		
	$p->clientId 		= $connection['mkClientID'];		
	$p->clientSecret 	= $connection['mkClientSecret'];		
	$p->programName 	= $connection['programName'];
	$p->source 			= $connection['source'];
	$p->reason 			= $connection['reason'];
	$p->input 			= array($lead1);
	
	$this->subresponse['leadinfo'] = json_encode($lead1);
	$this->subresponse['programName'] = $connection['programName'];
	$this->subresponse['marketoresponse']=$p->postData();
	$this->subresponse['host'] = $hostname;
	$this->subresponse['mkConnection'] = json_encode($connection);
	$this->subresponse['mkMapping'] = json_encode($mapping);
	
	} else {
	
	$this->subresponse['mrktosuccess'] = 'false';
	$this->subresponse['mrktofailuremsg'] = 'One or more required fields was empty.';	
		
	}
	
	return json_encode($this->subresponse);
	
}

function sendEmail($num,$recipient,$emailsubject,$emailfrom,$body,$attachment=NULL,$replyto=NULL){
	$subresponse = array();
	  $emailStartTime = microtime(true);

		$this->subresponse['sentTo1'] = $recipient;

		if (strpos($emailfrom, '@') == false) { 
			$emailfrom = $emailfrom . '@theroishop.com';
		} 
		else { 
			$emailfrom = $emailfrom; 
		} 


	  $recipients = array();

		$emails = preg_split('/[;,]/', $recipient);
		$x=0;
		foreach($emails as $email){
		 //check and trim the Data
		 $valid=false;
		 if (filter_var($email, FILTER_VALIDATE_EMAIL)){
		 $valid=true;
		 }
		 if($valid){
		  $recipients[] = trim($email);
		  $x = $x = 1;
		  // do something else if valid
		 }else{
		  // Error-Handling goes here
		 }
		}	
		
	  $this->subresponse['sentTo'] = $recipients;
		
	  try{
	 
		  
		
		  //Create the Transport
		  $transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
		
		  //Create the Mailer using your created Transport
		  $mailer = Swift_Mailer::newInstance($transport);
		 
		  //Create the message
		  $message = Swift_Message::newInstance()
		  ->setSubject($emailsubject)
		  ->setFrom(array($emailfrom => $emailfrom))
		  ->setTo($recipients)
		  ->setBody($body, 'text/html');

		  if ($attachment && strlen($attachment)>1) {$message->attach(Swift_Attachment::fromPath($attachment));}
		  if ($replyto && strlen($replyto)>1) {$message->setReplyTo(array($replyto => $emailfrom));}
	

		  $mailer->send( $message );	
		  $this->subresponse['success'] = 'success';
		  $this->subresponse['sentTo'] = $recipients;
		  $this->subresponse['sentFrom'] = $emailfrom;
		  $this->subresponse['subject'] = $emailsubject;
		  $this->subresponse['replyTo'] = $replyto;
		  $this->subresponse['attachment'] = $attachment;
		  $response = 'success';
		  
		  }catch(Exception $e){
		  $this->subresponse['success'] = 'failed';
			$response = 'failed';
		  
		  }finally {
		  	
		  }
		  
		$emailEndTime = microtime(true);
		$emailseconds = $emailEndTime - $emailStartTime;
		$this->subresponse['elapsedTime'] = $emailseconds;
		
		return json_encode($this->subresponse);
	//*/
	
}


function createPDF(){
	
	//Need a standard PDF creation routine.
	
	//Also need to create a table in the database to store outputs for all macros that are run.
	
}

function FinishROI($macroID, $instanceID, array $aurgs) {

	$functionStartTime = microtime(true);

	

	global $formatter;

	$g = new GeneralFunctions();
	$wbroiID = $g->GetWbroiIDFromInstanceID($instanceID);
	$macrostatus = $g->DLookup('macrostatus','wb_roi_reports_macros','usedmacroID=' . $macroID);
	$this->response['macrostatus'] = $macrostatus;
	$this->response['instanceID'] = $instanceID;
	if($macrostatus==0) {$this->response['errorlog']=1;}
	
	//1 -  Name: Complete ROI
	/**
	 * 		Name: 			Complete ROI
	 * 		Description: 	This macro 
	 * 						1. checks that all required fields are completed, 
	 * 						2. creates the customer pdf, 
	 * 						3. sends an email to the customer, 
	 * 						4. sends an email to the ROI owner (or designee), and 
	 * 						5. redirects the page to a followup page.
	 */	
	
	
	if($aurgs['1']==1){
		//Check that required fields are not null
								
		$emptycount = $g->CountRequiredNotCompleted($macroID, $instanceID);											//PDO OK
		
		$functionCountempty = microtime(true);
		$seconds = $functionCountempty - $functionStartTime;
		$this->response['CountEmptyTime'] = $seconds . ' seconds';
		
		if($emptycount>0) {
			$immedstop = 1;
			
			$modalreport = $aurgs['15'];
			$modal = $g->DLookup('HTML','wb_roi_reports','wb_roi_report_ID=' . $modalreport);
	
			$modal = '<div id="macromodal" class="modal fade" aria-hidden="true">
                  <div class="modal-dialog">
                  <div class="modal-content">
                  <div class="modal-body">
                    <div id="modalcontent">' . $modal . '</div>
                  </div>
                  </div>
                  </div>
                  </div>';
			
			
			$this->response['nextaction']=3; 	//3=Open Modal About all fields required
			$this->response['UnCompletedFields'] = 'true';
			$this->response['modal'] = $modal;
		}
		
	}
	else {
		//Do Not check required
		$immedstop = 0;
	}
	
	
	if($immedstop>0){
		return json_encode($this->response);
	}
	
	else{
		//Create the customer pdf	
		$pdfStartTime = microtime(true);
		
		//Get the report to be pdfed from the aurgument of the macro
		$pdfreportID	= $aurgs['2'];
		//retreive the css and html of the pdf report
		$reportCSS 		= $g->Dlookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $pdfreportID);
		$reportHTML 	= $g->Dlookup('html','wb_roi_reports','wb_roi_report_ID=' . $pdfreportID);
		
		//Get the Customer Email Report
		$emailreportID		= $aurgs['3'];  
		$cust_emailbody		= $g -> DLookup('HTML','wb_roi_reports','wb_roi_report_ID=' . $emailreportID);
		  
		//Get the ROI Owner Email Report
		$emailreportID		= $aurgs['5'];  
		$client_emailbody	= $g -> DLookup('HTML','wb_roi_reports','wb_roi_report_ID=' . $emailreportID);
		

		
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
			$cust_emailbody 	= str_replace('<tag>' . $r['field'] . '</tag>', $r['formatted_value'], $cust_emailbody);
			$client_emailbody 	= str_replace('<tag>' . $r['field'] . '</tag>', $r['formatted_value'], $client_emailbody);    
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
			$cust_emailbody = str_replace('<tagstd>' . $r['stdfieldID'] . '</tagstd>', $r['value'], $cust_emailbody);
			$client_emailbody = str_replace('<tagstd>' . $r['stdfieldID'] . '</tagstd>', $r['value'], $client_emailbody);
		  }
		}
		
		//Need to loop through the fields that are used in this report and replace accordingly
		 $SQL = "SELECT * 
	    		FROM `wb_roi_reports_fieldusage` t1
	    		JOIN `wb_roi_instance_values` t2 ON t1.fieldID=t2.field
	    		WHERE t1.reportID=$pdfreportID AND t2.instanceID=$instanceID;";
		
		//echo $SQL;
		$list = $g->returnarray($SQL);
	
		$numrows = count($list);
		$x=0;
		if($numrows>0){
		  foreach($list as $r){
			$x = $x + 1;	
			$reportHTML = str_replace('<tagrpt>' . $r['usageID'] . '</tagrpt>', $formatter->format($r['value'],$r['numeralFormat']) , $reportHTML);
			$cust_emailbody = str_replace('<tagrpt>' . $r['usageID'] . '</tagrpt>', $formatter->format($r['value'],$r['numeralFormat']) , $cust_emailbody);
			$client_emailbody = str_replace('<tagrpt>' . $r['usageID'] . '</tagrpt>', $formatter->format($r['value'],$r['numeralFormat']) , $client_emailbody);
		  }
		}
		
		//Finalize the report to be pdfed
		$report = '<html><head>' . $reportCSS . '</head><body>' . $reportHTML . '</body></html>';
			
		$stylesheet = file_get_contents('../assets/css/pdfstyle.css');
		$comp_stylesheet = file_get_contents('../assets/css/style.css');
		
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
		
		if ($pdfreportID>0){
		
		//Get the name of the file that should be created
		$filename = $g->DLookup('filename','wb_roi_reports','wb_roi_report_ID=' . $pdfreportID);		
		
		$mpdf = new mPDF('c', $page);
			
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($comp_stylesheet,1);
			
		$mpdf->WriteHTML($report);
		
		$mpdf->Output('../assets/customwb/' . $wbroiID . '/pdf/' . $wbroiID . '-' . $instanceID . '.pdf','F');
		$mpdf->Output('../assets/customwb/' . $wbroiID . '/pdf/' . $filename . '.pdf','F');
		
		$savedfile = $g->StorePDFNameValue($instanceID,'http://theroishop.com/webapps/assets/customwb/' . $wbroiID . '/pdf/' . $wbroiID . '-' . $instanceID . '.pdf');
		$cust_emailbody 	= str_replace('<tagstd>17</tagstd>', $savedfile, $cust_emailbody);
		$client_emailbody 	= str_replace('<tagstd>17</tagstd>', $savedfile, $client_emailbody);
		
		$fileattach = '../assets/customwb/' . $wbroiID . '/pdf/' . $filename . '.pdf';
		
		}
		else {
			
		$fileattach = '';	
			
		}
		
		$pdfEndTime = microtime(true);
		$pdfseconds = $pdfEndTime - $pdfStartTime;
		$this->response['pdfcreationtime'] = $pdfseconds;
		
		  // *******************Send Email to ROI Owner **********************************
		if($aurgs['14']==1){
		  //Lookup the right email address
		  //Get the submital email from the aurguments of the macro
		  $client_email 	= $aurgs['7'];
		  
		  //Get the message from the macro aurguments
		  $emailsubject 	= $aurgs['6'];
		  

		  $this->response['email1'] = $this->sendEmail(1,$client_email,$emailsubject,'ExpressROI',$client_emailbody,$fileattach);
		}
		  // *******************Send Email to Customer **********************************
		if($aurgs['13']==1){
		  //Lookup the right email address
 
		  //Now get the value of this field for this instance
		  $customer_email 	= $g->Dlookup('value','wb_roi_instance_values','field=' . $aurgs['12'] . ' AND instanceID=' . $instanceID);
		  
		  //Get the message from the macro aurguments
		  $emailsubject 	= $aurgs['4'];
		  
		  //Get the from email address the client would prefer to use.
		  $emailfrom 	= $aurgs['10'];
		  
		  //Get the replyto email address the client would prefer to use.
		  $replyto 	= $aurgs['17'];
		  
		  //The body of the email was already retrieved and tags replaced as needed

		  $this->response['email2'] = $this->sendEmail(2,$customer_email,$emailsubject,$emailfrom,$cust_emailbody,$fileattach,$replyto);
			
		  $this->response['cust_email'] = 	$customer_email;
		}
		  // *******************Pass Completion variables back to the page **********************************
		
		  $this->response['nextaction'] 	= $aurgs['8']/1;
		  
		  
		  
		  switch ($aurgs['8']) {
		    
		    case 1:
				//Create an alert
				$this->response['alert'] = $aurgs['9'];       
		        break;
			case 2:
				//redirect to new page
				
				$this->response['address'] = $aurgs['9'];
				
				//Check to see if the next address is a field
				
				if(substr($aurgs['9'],0,1)=='[') {
					//Assume this refers to a field
					$addressfield1 = str_replace('[','',$aurgs['9']);
					$addressfield2 = str_replace(']','',$addressfield1);
					$this->response['addressfieldID'] = $addressfield2;
					$this->response['address'] = $g->Dlookup('formatted_value','wb_roi_instance_values','field=' . $addressfield2 . ' AND instanceID=' . $instanceID);
				}
				
				
				break;
			case 3:
				//Open a modal
				$this->response['modalreport'] = $aurgs['9'];
				
				$modalreport = $aurgs['9'];
				$modal = $g->DLookup('HTML','wb_roi_reports','wb_roi_report_ID=' . $modalreport);
	
				$modal = '<div id="macromodal" class="modal fade" aria-hidden="true">
                  <div class="modal-dialog">
                  <div class="modal-content">
                  <div class="modal-body">
                    <div id="modalcontent">' . $modal . '</div>
                  </div>
                  </div>
                  </div>
                  </div>';
				
				$this->response['nextaction']=3; 	//3=Open Modal About all fields required
				//$this->response['modal'] = $modal;
				
				break;
			case 5:
				//display a report on this page
				$this->response['instanceid'] = $instanceID;
				$this->response['reportid'] = $aurgs['9'];
				$this->response['wbappid'] = $wbroiID;
				break; 	
		    default:
		        $this->response['address'] = 'http://www.theroishop.com';
		}
		  
		  
			
		
		  
		//*/
	} //End if immedstop==0
	

	$mkConnection = $aurgs['11'];
	
	
	
	
	if($mkConnection>0){
		$this->response['marketo'] = $this->PushLeadToMarketo($instanceID, $mkConnection);
		$mktoresponse = $this->response['marketo'];
		//$mktoresponse = json_encode($mktoresponse);
		$g->InsertMacroResponse($wbroiID, $instanceID, 0, $mktoresponse);
	}

	$macroresponse = json_encode($this->response);
	$g->InsertMacroResponse($wbroiID, $instanceID, 0, $macroresponse);
	
	
	//return $marketo;
	
	return json_encode($this->response);
	//*/
}








 





























}

?>