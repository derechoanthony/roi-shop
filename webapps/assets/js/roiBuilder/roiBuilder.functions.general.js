/*
 *
 *   ROI Builder Functions
 *   version 2.2
 *
 */


$(document).ready(function () {
	
	//Assign element properties click function


	$('.changereport').click(function(){
		console.log('Changing Report');
		var reportID = $(this).data('reportid');
		console.log(reportID);
		//Reload the editor with the correct page
		$.ajax({
			type	: 	"POST",
			url		:	"wb_roi_details_ajax_editor.php",
			data	:	'roiReportID='+reportID,
			success	:	function(resulthtml) {
				console.log(resulthtml);
				//refresh list of elements
				$('#editor').html(resulthtml);
				
				}
        	});
        	
        $.ajax({
			type	: 	"POST",
			url		:	"wb_roi_details_ajax_elements.php",
			data	:	'roiReportID='+reportID,
			success	:	function(resulthtml) {
				console.log(resulthtml);
				//refresh list of elements
				$('#elementlist').html(resulthtml);
				
				}
        	});
        	
	});
	
	
		$('.select_element').click(function(){
		console.log('Selecting Element');
		var elementID = $(this).data('elemid');
		console.log(elementID);
		//Reload the editor with the correct page
		$.ajax({
			type	: 	"POST",
			url		:	"wb_roi_details_ajax_elementdetailspane.php",
			data	:	'elemID='+elementID,
			success	:	function(resulthtml) {
				console.log(resulthtml);
				//refresh list of elements
				$('#elementdetails').html(resulthtml);
				
				}
        	});

		});

});

