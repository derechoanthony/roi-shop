
<?php include '../inc/head.php'; 
$roiID = $_GET['wbappID'];
$key 	= $_GET['key'];
$tabname='edit';
?>



<div class="row wrapper border-bottom white-bg page-heading">
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

<div class="row">
	<div class="col-sm-12">
		<?php include '../inc/detailsmenu.php';?>
	</div>
</div>
	
<div class="row">
<div class="col-sm-12">
    	<input type="hidden" id="roiID" value="<?php echo $roiID; ?>"></input>
		<?php include 'details_tab_edit.php';?>
 
            </div>


</div>
            
        </div>

<?php include '../inc/footer.php';?>

    <!-- CodeMirror -->
    <script src="../assets/js/plugins/codemirror/codemirror.js"></script>
    <script src="../assets/js/plugins/codemirror/mode/javascript/javascript.js"></script>

</body>

</html>
<script>
         $(document).ready(function(){
			console.log('code view');
             
			var editor_one = CodeMirror.fromTextArea(document.getElementById("csshtml"), {
                 lineNumbers: true,
                 matchBrackets: true,
                 styleActiveLine: true,
                 
             });

             var editor_two = CodeMirror.fromTextArea(document.getElementById("html"), {
                 lineNumbers: true,
                 matchBrackets: true,
                 styleActiveLine: true
             });
             
             var editor_three = CodeMirror.fromTextArea(document.getElementById("script"), {
                 lineNumbers: true,
                 matchBrackets: true,
                 styleActiveLine: true
             });
             
             
         
         
         
             
         $('.savehtmlcode').click(function(){
		console.log ('save code');

		
		//var roiID = $('#roiID').val();
		var reportID 	= $('#selectedreport').val();
		var css 		= editor_one.getValue();
		editor_one.save();
		var css1		= $('#csshtml').val();
		var html 		= editor_two.getValue();
		editor_two.save();
		var html1		= $('#html').val();
		var script 		= editor_three.getValue();
		editor_three.save();
		var script1		= $('#script').val();
		//console.log ('css:' + css1);
		$.ajax({
			type	: 	"POST",
			url		:	"../php/ajax_savecode.php",
			data	:	{reportid: reportID,
						csshtml: css1,
						html: html1,
						script: script1},
			success	:	function(returnhtml) {
				console.log(returnhtml);
				
				
			}
		

		
	});
		
		

	
	});
             

        });
   </script>



                                		
