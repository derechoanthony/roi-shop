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
	

function OpenModal($varstring){
	
	$g1 = new GeneralFunctions();
	//$outputs = array();
	//extract variables
	$vars = explode('<;>',$varstring);
	
	//aurguments list
	//0 - elementID
	//1 - reportID of modal
	
	$modal = $g1->DLookup('HTML','wb_roi_reports','wb_roi_report_ID=' . $vars[1]);
	
	$modal = '<div id="macromodal" class="modal fade" aria-hidden="true">
                  <div class="modal-dialog">
                  <div class="modal-content">
                  <div class="modal-body">
                    <div id="modalcontent">' . $modal . '</div>
                  </div>
                  </div>
                  </div>
                  </div>';
	
	$this->response['nextaction']=3; 	//1=Open Modal Window	
	$this->response['modal'] = $modal;
	return json_encode($this->response);

}


function PushLeadToMarketo($instanceID,$mkConnectionID){

	$g = new GeneralFunctions();
	
	$connection = $g->getMarketoConnection($mkConnectionID);
	$mapping 	= $g->getMarketoMapping($instanceID,$mkConnectionID);
	
	$lead1 = array();
	foreach($mapping as $r){
		$lead1[$r['mkfieldName']] = $r['selectedvalue'];
	}
	
	$p = new PushLeads();
	$hostname = "https://" . $connection['mkHost'] . ".mktorest.com";
	$p->host 			= $hostname;		
	$p->clientId 		= $connection['mkClientID'];		
	$p->clientSecret 	= $connection['mkClientSecret'];		
	$p->programName 	= $connection['programName'];
	$p->source 			= $connection['source'];
	$p->reason 			= $connection['reason'];
	$p->input 			= array($lead1);
	
	$this->response['leadinfo'] = json_encode($lead1);
	$this->response['programName'] = $connection['programName'];
	$this->response['marketoresponse']=$p->postData();
	$this->response['host'] = $hostname;
	$this->response['mkConnection'] = json_encode($connection);
	$this->response['mkMapping'] = json_encode($mapping);
	
	return json_encode($outputs);
	
}

function sendEmail($recipient,$emailsubject,$emailfrom,$body,$attachment=NULL){
	

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

		  if ($attachment) {$message->attach(Swift_Attachment::fromPath($attachment));}

		  $mailer->send( $message );	
		  
		  return  'success';
		  }
		  }catch(Exception $e){
		  
		  return  'failed';
		  
		  }finally {
		  	
		  }
	//*/
	
}


function createPDF(){
	
	//Need a standard PDF creation routine.
	
	//Also need to create a table in the database to store outputs for all macros that are run.
	
}

function FinishROI($instanceID, array $aurgs) {

	$g = new GeneralFunctions();
	$wbroiID = $g->GetWbroiIDFromInstanceID($instanceID);

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
								
		$emptycount = $g->CountRequiredNotCompleted($instanceID);											//PDO OK

		if($emptycount>0) {
			$immedstop = 1;
			$this->response['nextaction']=1; 	//1=Create alert
			$this->response['alert'] = 'Please Complete All Required Fields';
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
				 
		  // *******************Send Email to ROI Owner **********************************
		if($aurgs['14']==1){
		  //Lookup the right email address
		  //Get the submital email from the aurguments of the macro
		  $client_email 	= $aurgs['7'];
		  
		  //Get the message from the macro aurguments
		  $emailsubject 	= $aurgs['6'];
		  

		  $this->response['email1'] = $this->sendEmail($client_email,$emailsubject,'ExpressROI',$client_emailbody,'../assets/customwb/' . $wbroiID . '/pdf/' . $filename . '.pdf');
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
		  
		  //The body of the email was already retrieved and tags replaced as needed

		  $this->response['email2'] = $this->sendEmail($customer_email,$emailsubject,$emailfrom,$cust_emailbody,'../assets/customwb/' . $wbroiID . '/pdf/' . $filename . '.pdf');
			
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
				break;
		    default:
		        $this->response['address'] = 'http://www.theroishop.com';
		}
		  
		  
			
		
		  
		//*/
	} //End if immedstop==0
	

	$mkConnection = $aurgs['11'];
	
	if($mkConnection>0){
		$this->response['marketo'] = $this->PushLeadToMarketo($instanceID, $mkConnection);
	}

	
	//return $marketo;
	return json_encode($this->response);
	//*/
}








 





























}

?>