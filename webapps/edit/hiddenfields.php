
<?php include '../inc/head.php'; 
$roiID = $_GET['wbappID'];
$key 	= $_GET['key'];
$tabname='edit';
?>


<div class="row wrapper border-bottom white-bg page-heading" id="wrap">
    <div class="col-lg-10">
        <h2>My WebApps</h2>
        <ol class="breadcrumb">
            <li>
                <a href="../index.php">Home</a>
            </li>
             <li>
                <a href="../mywebapps/index.php">WebApp List</a>
            </li>
           
            <li class="active">
                <strong><?php echo $g->Dlookup('roiName','wb_roi_list','wb_roi_ID='.$roiID);?></strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>	

<div class="wrapper wrapper-content animated fadeInRight">



<!-- Start Form -->


	<?php 
                        
        $SQL = "SELECT *                      		
        		FROM wb_roi_fields t1 
        		WHERE wb_roi_ID=$roiID AND fieldType>1
				ORDER BY cellcolumn ASC, cell ASC;";
				
		$list = $g->returnarray($SQL);
        $listitems = '';
		
		$numrows = count($list);
        $x = 0;
		
		
		if($numrows>0){
			foreach($list as $r){
				
				$x = $x + 1;
				$listitems = $listitems .'<label class="col-sm-9 do-control-label2">' . $r['Label'] . '</label>';
				$listitems = $listitems .'	<input';
            	$listitems = $listitems .'	tabindex=' . $x;
            	$listitems = $listitems .'  id="input' . $r['cellcolumn'] . $r['cell'] . '"';
				$listitems = $listitems .'  name="' . $r['fieldID'] . '"';
				$listitems = $listitems .'  data-cell="' . $r['cellcolumn'] . $r['cell'] . '"';
				$listitems = $listitems .'  type="text"';
				$listitems = $listitems .'  class="form-control roishop-wb-field b-r-sm doinput2"';
				$listitems = $listitems .'   data-formula="<formulatag>' . $r['fieldID'] . '</formulatag>"';
				$listitems = $listitems .'   data-format="$0,0"';
				$listitems = $listitems .'   placeholder=""';
				$listitems = $listitems .'   >';
				
				}
		}
		
		
		echo htmlspecialchars($listitems);

        ?>	
	
	
	
	




<!-- End Form -->







	

            
        </div>

<?php include '../inc/footer.php';?>

    <!-- CodeMirror -->
    <script src="../assets/js/plugins/codemirror/codemirror.js"></script>
    <script src="../assets/js/plugins/codemirror/mode/javascript/javascript.js"></script>
	<!-- Jasny (File Uploader) -->
    <script src="../assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>
    
</body>

</html>



                                		
