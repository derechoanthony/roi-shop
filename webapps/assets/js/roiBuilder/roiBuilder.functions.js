/*
 *
 *   ROI Builder Functions
 *   version 2.2
 *
 */


$(document).ready(function () {
	
	//Assign element properties click function
	bindClasses();
	
	//Toggle the grid view
	$('.toggle-grid').click(function() {
		console.log('working');
		//Find the page div by id
		//And toggle the class show-grid
		$('.section-content').each(function(i, obj) {
		    $(this).toggleClass('show-grid');
		});
		$('.col-label').each(function(i, obj) {
		    $(this).toggleClass('hidden');
		});
	});

	//Highlight the selected Section (Column)
	$('.roicolumn').click(function(e) {
		e.stopPropagation();
		console.log('trying to highlight');
		//Find the page div by id
		//And toggle the class show-grid
		$('.roicolumn').each(function(i, obj) {
		    $(this).removeClass('selected');
		});
		$(this).addClass('selected');
		$('#roiColumnID').val($(this).data('elemid'));
	});

	$('.page-tab').click(function(){
		console.log('getting Page');
		var roiPageID = $(this).data('roipageid');
		console.log(roiPageID);
		$('#roiPageID').val($(this).data('roipageid'));
	});

	$('.newelem').click(function(){
		console.log('new element');
		
		var elemType = $(this).data('elemtype');
		var roiVersionID = 1;
		//var roiPageID = $('#roiPageID').val();
		//var roiParentID = $('#roiColumnID').val();
		console.log(elemType);
		//Add the element to the database
		$.ajax({
			type	: 	"POST",
			url		:	"../../php/roi_creator_pages/element-new.php",
			data	:	'roiVersionID='+roiVersionID+'&elementType='+elemType,
			success	:	function(resulthtml) {
				console.log(resulthtml);
				//refresh list of elements
				$('#elementList').html(resulthtml);
				bindClasses();
				}
        	});
	});

    // Close ibox function
    $('.NewSection').click(function () {
        var loc = $(this).data('loc');
        console.log (loc);
        //var roiVersionID = getUrlVars()['roiVersionID'];
        //var roiPageID = $('#roiPageID').val();
        //If this goes to the top increase the order number 
        //of all rows in this section by 1
        if(loc == 1){
        	//Do an ajax call to increase the order num
        	//Then insert a column with a parent_elementID equal to the newly inserted row
        	
        	$.ajax({
			type	: 	"POST",
			url		:	"../php/constructors/roiBuilder/new_section.php",
			data	:	'roiVersionID='+getUrlVars()['roiVersionID'],
			success	:	function(sql) {
				console.log (sql);
				}
        	});
        	
        }
        else {
        	//Do an ajax call to add the row and column
        }
        
        //Redraw the section.
        
    });//*/

    
});


function bindClasses(){
	$('.select_element').click(function(){
		selectElement($(this));
	});
	
	$('.delete_element').click(function(){
		deleteElement($(this));
	});
	
}


function selectElement(thisobj){
	
	//Get elemenType and elementID
	var elemid 		= thisobj.data('elementid');
	var elemtype 	= thisobj.data('elementtype');
	var elemname	= thisobj.data('elementname')+ ' ';
	
	var listelem	= thisobj.closest("ul");
	
	//alert(listelem);
	
	listelem.children('.selected').each(function(){
		$(this).removeClass('selected');
	});
	
	thisobj.parent().toggleClass('selected');
	//Use the element Type to perform an ajax call to 
	//get the correct Element Properties Window
	console.log (elemtype);
	
	$.ajax({
			type	: 	"POST",
			url		:	"../../php/roi_creator_pages/element-select.php",
			data	:	'elemID='+elemid+'&elemtype='+elemtype,
			success	:	function(resulthtml) {
				$('#ElemPropertiesDialog').html(resulthtml);
				//Change the Label for the property box
				$('#proplabel').html(elemname);
				$('#elemidlabel').html(elemid);
				
				
				}
        	});
	
}

function deleteElement(){
	//Get selected Element
	var elemid			= $('#elemidlabel').html();
	var roiVersionID	= 1; //Need to get from URL
	console.log('delete ' + elemid);
	//Need to add dialog to confirm delete
	
	//Delete this elementID from the database
	
	$.ajax({
			type	: 	"POST",
			url		:	"../../php/roi_creator_pages/element-delete.php",
			data	:	'elemID='+elemid +'&roiVersionID=' + roiVersionID,
			success	:	function(resulthtml) {
				console.log('deleted');
				$('#elementList').html(resulthtml);
				//Bind appropriate classes to functions
					bindClasses();
				
				
				}
        	});
	
}

