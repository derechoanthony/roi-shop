
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/sandwebapp/core/init.php" ); 									// Sets up connection to database

$wbappID 	= $_POST['wbappID'];

$roiName	= $g->DLookup('roiName','wb_roi_list','wb_roi_ID=' . $wbappID);
$code		= $g->DLookup('roiHTML','wb_roi_list','wb_roi_ID=' . $wbappID);
$addcodeview = '<form name="updatecode"> <textarea id="code1" name="htmlcode">' . $code . '</textarea></form>';

	$returnhtml = '<div class="panel panel-default">
        
            <div class="panel-heading">
            	
            	<div class="ibox-tools">
            		
            		<div class="form-group pull-right">

			        <div class="btn-group">
			            
			            
			        </div>
			        </div>
            		
                    
                </div>
                <strong>'  . $roiName . '</strong> Code View
                
            </div>
            <div class="panel-body" id="code-panel"> ' . 
            
            
            
            $addcodeview . '
               
                
            </div>
			
			<div class="panel-footer">
				<span class="pull-right"><a class="btn btn-primary btn-bitbucket savecode" id="savecodebutton"><i class="fa fa-save"></i> Save</a></span>
                <a class="btn btn-primary btn-bitbucket viewresult"><i class="fa fa-desktop"></i> Full Screen</a>
                <a class="btn btn-default btn-bitbucket viewcode"><i class="fa fa-code"></i> View Code</a>
                <a class="btn btn-primary btn-bitbucket"><i class="fa fa-refresh"></i> Reload</a>
            </div>
			
        </div>';

echo $returnhtml;

?>

