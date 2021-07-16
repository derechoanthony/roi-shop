
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
	<!-- Jasny (File Uploader) -->
    <script src="../assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>
    
</body>

</html>
<script>


$(document).on('change', '.formattype', function(){
    var wbappid 		= $('#roiID').val();
         	var fieldtype		= $(this).val();
         	console.log('getting type');
         	$.ajax({
			type	: 	"POST",
			url		:	"ajax_fieldtypeview.php",
			data	:	{wbappid: 	wbappid,
						 fieldtype: fieldtype
						},
			success	:	function(returnhtml) {
				console.log('done with field type');
				$('#formattypehtml').html(returnhtml);
				
				
			}
		
			}); //End Ajax
});



         $(document).ready(function(){
         	
         	
         	var getUrlParameter = function getUrlParameter(sParam) {
			    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			        sURLVariables = sPageURL.split('&'),
			        sParameterName,
			        i;
			
			    for (i = 0; i < sURLVariables.length; i++) {
			        sParameterName = sURLVariables[i].split('=');
			
			        if (sParameterName[0] === sParam) {
			            return sParameterName[1] === undefined ? true : sParameterName[1];
			        }
			    }
			};
         	
         	
         	var editor_one = CodeMirror.fromTextArea(document.getElementById("csshtml"), {
                 lineNumbers: true,
                 lineWrapping: true,
                 matchBrackets: true,
                 theme: 'ambiance',
                 styleActiveLine: true,
                 
             });
             //editor_one.refresh();
             
             
             var editor_two = CodeMirror.fromTextArea(document.getElementById("html"), {
                 lineNumbers: true,
                 lineWrapping: true,
                 matchBrackets: true,
                 theme: 'ambiance',
                 styleActiveLine: true
             });
              //editor_two.refresh();
              
             var editor_three = CodeMirror.fromTextArea(document.getElementById("script"), {
                 lineNumbers: true,
                 lineWrapping: true,
                 matchBrackets: true,
                 theme: 'ambiance',
                 styleActiveLine: true
             });
              //editor_three.refresh();
         	
             
             
         
         $('.editfieldview').click(function(){
         	var wbappid 		= $('#roiID').val();
         	
         	$.ajax({
			type	: 	"POST",
			url		:	"ajax_fieldeditview.php",
			data	:	{wbappid: wbappid
						},
			success	:	function(returnhtml) {
				$('#edithtml').html(returnhtml);
				
				
			}
		
			}); //End Ajax
         	
         	
         });
         
         
         $('.formattype').click(function(){
         	var wbappid 		= $('#roiID').val();
         	var fieldtype		= $(this).val();
         	console.log('getting type');
         	$.ajax({
			type	: 	"POST",
			url		:	"ajax_fieldtypeview.php",
			data	:	{wbappid: 	wbappid,
						 fieldtype: fieldtype
						},
			success	:	function(returnhtml) {
				console.log('done with field type');
				$('#formattypehtml').html(returnhtml);
				
				
			}
		
			}); //End Ajax
         	
         	
         });
         
         
         
             
         $('.savehtmlcode').click(function(){

			
		
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
		
		}); //End Ajax
		
		

	
	});//End Save html Code Function
	

$('.closearchivemodal').click(function(){
	//var modalname = $("#modal-savearchive").modal();
	$('#modal-savearchive').modal('hide');
})

		$('.newmacro').click(function(){

			
		
		var wbappid 		= $('#roiID').val();
		console.log ('roi: ' + wbappid);
		var macroname 	= $('#macroname').val();
		var macroid 	= $('#macroid').val();
		
		$.ajax({
			type	: 	"POST",
			url		:	"ajax_newmacro.php",
			data	:	{
						wbappid:		wbappid,
						macroname: 		macroname,
						macroid: 		macroid,
						},
			success	:	function(returnhtml) {
				//Get New list of archives
				$('#macrolist').html(returnhtml);
				console.log(returnhtml);
				//Close modal
				$('#modal-newmacro').modal('hide');
				
			}
		
		}); //End Ajax
	});//End Add New Macro Function

$('.macroval').change(function(){
		var aurgid 		= $(this).data('aurgid');
		var newval		= $(this).val();
		console.log ('aurgid: ' + aurgid);
		console.log ('new value: ' + newval);
		
		
		$.ajax({
			type	: 	"POST",
			url		:	"ajax_savemacroaurgvalue.php",
			data	:	{
						aurgid:		aurgid,
						newval: 	newval
						},
			success	:	function(returnhtml) {
				//Get New list of archives
				//$('#macrolist').html(returnhtml);
				console.log(returnhtml);
				
				
			}
		
		}); //End Ajax
		
		
	});//End Add New Macro Function


         $('.savearchive').click(function(){

			
		
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
		var archivename	= $('#archivename').val();
		var archivedesc	= $('#archivedesc').val();
		//console.log ('css:' + css1);
		var modalname = $("#modal-savearchive").modal();
		$.ajax({
			type	: 	"POST",
			url		:	"ajax_savearchive.php",
			data	:	{
						reportid: 		reportID,
						archivename: 	archivename,
						archivedesc: 	archivedesc,
						csshtml: 		css1,
						html: 			html1,
						script: 		script1,
						listonly: 		0
						},
			success	:	function(returnhtml) {
				//Get New list of archives
				$('#archivelist').html(returnhtml);
				console.log(returnhtml);
				//Close modal
				$('#modal-savearchive').modal('hide');
				
			}
		
		}); //End Ajax
		
		

	
	});//End Save Archive Code Function
	
	
             
//Begin Preview Function
$('.preview').click(function(){

	var reportID 	= $('#selectedreport').val();
	var reportType 	= $('#reportType').val();
	var dt 			= new Date();
	//If reportType = 0 This is an html report
	//Preview in a blank window the icalc page.
	
	console.log ('reportType: ' + reportType);
	
	if(reportType==0){
		var win = window.open('../icalc/icalc.php?wbappID=<?php echo $roiID;?>&key=<?php echo $key;?>&r=' + reportID, '_blank');
  		win.focus();	
	 }
	
	//If reportType = 1 this is a pdf report
	//create the pdf.  Name it preview_reportID and open it in a blank window
	if (reportType==1){
		
		$.ajax({
			type	: 	"POST",
			url		:	"../edit/pdfpreview.php",
			data	:	{reportid: reportID},
			success	:	function(returnhtml) {
				//console.log (returnhtml);
				var win = window.open('../edit/pdf_previewer.php?reportID=' + reportID, '_blank');
  				win.focus();
				
				
			}
		
		}); //End Ajax
		
	}
	
	//If reportType = 3 This is a modal window
	//Preview in a modal window.
	
	if(reportType==4){
		//Place the code in a holder div
		editor_two.save();
		var modal		= $('#html').val();
		
		var modalhtml = '<div id="macromodal" class="modal fade" aria-hidden="true">';
            modalhtml = modalhtml + '<div class="modal-dialog">';
            modalhtml = modalhtml + '<div class="modal-content">';
            modalhtml = modalhtml + '<div class="modal-body">';
            modalhtml = modalhtml + '<div id="modalcontent">';
            modalhtml = modalhtml + modal
            modalhtml = modalhtml + '</div></div></div></div></div>';
		$('#modalholder').html(modalhtml);
		//Show the modal
		$('#macromodal').modal('show');		
			
	 }
	 
	 //If reportType = 3 This is an email report
	//Send a test email.
	
	if(reportType==3){
		//Place the code in a holder div
		editor_two.save();
		
		$.ajax({
			type	: 	"POST",
			url		:	"../edit/ajax_emailpreview.php",
			data	:	{reportid: reportID},
			success	:	function(returnhtml) {
				//console.log (returnhtml);
				console.log(returnhtml);
			}
		
		}); //End Ajax	
			
	 }


}); 

//End Preview Function

//Begin Preview Function
$('.changereportID').click(function(){

	var reportID 	= $(this).data("reportid");
	
	$.ajax({
			type	: 	"POST",
			url		:	"../edit/ajax_changereport.php",
			data	:	{reportid: reportID},
			success	:	function(returnhtml) {
				//Change the value of the div
				$('#reportspecs').html(returnhtml);
				
			}
		
		}); //End Ajax
	
	$.ajax({
			type	: 	"POST",
			url		:	"../edit/ajax_changereport_code.php",
			data	:	{reportid: reportID,
						codetype: 'CSS'},
			success	:	function(returnhtml) {
				//Update the CodeMirror
				editor_one.setValue(returnhtml);
				
			}
		
		}); //End Ajax
	
	$.ajax({
			type	: 	"POST",
			url		:	"../edit/ajax_changereport_code.php",
			data	:	{reportid: reportID,
						codetype: 'HTML'},
			success	:	function(returnhtml) {
				//Update the CodeMirror
				editor_two.setValue(returnhtml);
				
			}
		
		}); //End Ajax
		
	$.ajax({
			type	: 	"POST",
			url		:	"../edit/ajax_changereport_code.php",
			data	:	{reportid: reportID,
						codetype: 'Scripts'},
			success	:	function(returnhtml) {
				//Update the CodeMirror
				editor_three.setValue(returnhtml);
				
			}
		
		}); //End Ajax
	$.ajax({
			type	: 	"POST",
			url		:	"ajax_savearchive.php",
			data	:	{reportid: reportID,
						listonly: 1},
			success	:	function(returnhtml) {
				//Update the CodeMirror
				$('#archivelist').html(returnhtml);
				
			}
		
		}); //End Ajax
	


}); 

//End Preview Function


//Begin Get Field Details
$('.btneditfield').click(function(){

	var fieldid 	= $(this).data("fieldid");
	console.log (fieldid);
	$.ajax({
			type	: 	"POST",
			url		:	"../edit/ajax_getfielddetails.php",
			data	:	{fieldid: fieldid},
			success	:	function(response) {
				var responseout = JSON.parse(response);
				$('.fieldctl').each(function(){
					var fieldname = $(this).attr("name");
						
					if($(this).attr('type')=='checkbox'){
						console.log('got checkbox');
						var checkstm = (responseout[fieldname]==1?true:false);
						cb=$(this);
						cb.val(cb.prop('checked', checkstm));
					};
					
					
					console.log('fieldname: ' + fieldname);
					$(this).val(responseout[fieldname]);
					console.log('value: ' + responseout[fieldname]);
				});
				console.log(responseout);
				//$('#reportspecs').html(returnhtml);
				
			}
		
		}); //End Ajax


}); 
//End Get Field Details





         	$( "#tabs-edit-content" ).tabs({
			   activate: function(event, ui) { 
			   	
			 
             editor_one.refresh();
             
             
             
              editor_two.refresh();
              
             
              editor_three.refresh();
             
             
             
			   	
			   	}
			});







        });
        

         
        
        
   </script>



                                		
