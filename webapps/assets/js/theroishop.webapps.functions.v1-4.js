function sendformdata () {
      
		//1. Get all values that have been given
		//   Loop through all fields with the class -- 'roishop-wb-field' 

		//var form 			 = $('#roiwebapp');
		var data 			 = $("#roiwebapp" ).serialize();				
		var instanceID 		 = $('#instanceID').val();	
		var roicalcID 		 = $('#roicalcID').val();
		var roicalcstatus 	 = $('#roicalcstatus').val();			
		var data_unformatted = '';
		
		
		
		
		
		$('.roishop-wb-field').each(function(){
			if($(this).hasClass('nosave')){}else{
			
			if ($(this).hasClass('notnumeral')){data_unformatted = data_unformatted + $(this).attr("name") + '=' + $(this).val() + '&';}else
			{data_unformatted = data_unformatted + $(this).attr("name") + '=' + numeral().unformat($(this).val()) + '&';}
			
			}
		});
		data_unformatted = data_unformatted.substring(0, data_unformatted.length - 1);
		
		//This will not get checkboxes that are not checked.  Need to handle that in the posting php
			
		//2. Send the data to 'ajax_instancedata for saving to the database'
		var form1 			 = $('#roimodal');
		var datamodal		 = $("#roimodal" ).serialize();
		
		data = data + '&' + datamodal;
		data_unformatted = data_unformatted + '&' + datamodal;
		
		//console.log ('data: ' + data);
		
		$.ajax({
		type	: 	"POST",
		url		:	"../php/ajax_instancedata_v1-2.php",
		data	:	{
					instanceID: instanceID,
					roicalcID: roicalcID,
					roicalcstatus: roicalcstatus,
					data_formatted: data,
					data_unformatted: data_unformatted
					},
		    success	:	function(sql) {
			//console.log(sql);
			
			
    
			
		}
	});
	
	
	
		//Send standard field data in case browser is closed early
		var instanceID 	= $('#instanceID').val();
		var fieldedits 	= $('#fieldedits').val();
		console.log ('field edits: ' + fieldedits);
		$.ajax({
		type	: 	"POST",
		url		:	"../php/ajax_setunload.php",
		data	:	{
					instanceID: instanceID,
					fieldedits: fieldedits
					},
		success	:	function(sql) {
			//console.log(sql);

		}
		});

  }

function runmacro(e) {
	//Do an ajax request that will lookup the macro and perform the necessary items
      
      var instanceid 	= $('#instanceID').val();
      var elementid		= e.data('elemid');
      
      //If has class .addspinner then change button to spinner
      if(e.hasClass("addspinner")){
      	
      	var orightml = e.html();
      	
      	if (e.hasClass("keepsize")) {
      
      	var setwidth = e.width();
        var setheight = e.height();
      }
    	e.html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        e.prop("disabled",true);

     if (e.hasClass("keepsize")) {
      	 e.width(setwidth);
         e.height(setheight);
      }
      	
      }
      
      
      $.ajax({
					
			type	: 	'POST',
			url		: 	'../macros/runmacro-v1.php',
			data	:	{ instanceid: instanceid, elementid: elementid},
			success	:	function(response) {
			  //On return look through the echo return to 
              //perform any necessary jquery commands  

              var responseout = JSON.parse(response);
              var nextaction = responseout['nextaction'];
			  
			  if(responseout['errorlog']==1){nextaction=0;}
			  
			  //0 - Just do a console.log
              //1 - create alert
              //2 - direct to another location
			  //3 - Open Modal window
              
              switch(nextaction) {
              	 case 0:
              	 	//Error finding;
              	 	if(responseout['allvars']==1){console.log('all macro outputs: ');
              	 									console.log (JSON.parse(response));}
              	
                  case 1:
                  //1 -- Create Alert
                      //alert (responseout['alert']);
                      break;
                  case 2:
                  //2 -- Direct to another location
                  //console.log('to location: ' + responseout['address']);
                      window.top.location.href = responseout['address'];
                      break;
                  case 3:
                  //3 -- Open A Modal Window
                  
                  	  //Step 1. Replace the ajaxholder div with the content of the modal to open
                  	  //Step 2a. Modal always has id of macromodal
                  	  //Step 2. Open the modal
                  	 console.log (JSON.parse(response));
                  	  $('#macromodal').modal('hide');
                  	  $('html, body').animate({ scrollTop: 0 }, 'fast');
                  	  $('body').removeClass('modal-open');
					  $('.modal-backdrop').remove();
					  
                  	  $('#modalholder').html(responseout['modal']);
                  	  $('.calcx').calx('update');
                  	  $('#macromodal').modal('show');
                  
                  	  if(e.hasClass("addspinner")){ 
                  	  
                  	  e.html(orightml);
        			  e.prop("disabled",false);
                  	  	
                  	  }
                  		
                  
                  break;
                  case 4:
                  	//Close a Modal Window
                  	
                  	var modalname = responseout['modalid'];
                  	$('#' + modalname).modal('hide');
                  break;
                  default:
                      //Nothing.
                      
                     
              }
              
              	
			}
		});
}

jQuery(document).ready(function($) {
	
	var instanceID=$('#instanceID').val();
	var curfocusval = '';
	
	$('.currency-change').blur(function(){

		var currencysel = $(this).val();
		numeral.language(currencysel);

		$('.calcx').calx('getSheet').update();
		$('.calcx').calx('getSheet').calculate();
		$(this).val(currencysel);
	});
	
	$('.closemodal').on('click', function(){
    	$('#macromodal').modal('hide');  
   	});
	
	$('.roimacro').on('click', function(){
    	runmacro($(this));  
   	});
	 
	
	$('#modalholder').on('blur', '.roishop-wb-field', function(){
    	sendformdata();
	});
	
	$('#modalholder').on('click', '.roimacro', function(){
    	runmacro($(this));
	});
	
	$('.roishop-wb-field').blur(function(){
		sendformdata();
	});
	
	
	$('.roishop-actiontracked').focus(function(){
		curfocusval = $(this).val();
	});
	
	
	$('.roishop-actiontracked').blur(function(){
		
		if(curfocusval==$(this).val()){var addchange=0;}else{addchange=1;}
		
		//console.log ('current focus value: ' + curfocusval);
		//console.log ('new value: ' + $(this).val());
		//console.log ('add change: ' + addchange);
		//console.log ('previous change value: ' + $(this).data('changed'));
		
		
		
		var curchanged = $(this).data('changed');
		var newchange = curchanged + addchange;
		

		$(this).data('changed',curchanged/1 + addchange);
			
		var totaleditted = 0;
		
		$('.roishop-actiontracked').each(function(){
			if ($(this).data('changed')>0) {totaleditted = totaleditted + 1;}
		});
		
		$('#fieldedits').val(totaleditted);
		
		//console.log ('number of editted fields: ' + totaleditted);
		
	});
	
	
	$('.calcx').calx({
		autoCalculateTrigger	:	'keyup',
		defaultFormat			:	'0,0[.]00',
		onAfterCalculate		:	function() {
		}
	});
	
	
	$(window).unload(function(){
		alert('unload');
		var instanceID 	= $('#instanceID').val();
		var fieldedits 	= $('#fieldedits').val();
		$.ajax({
		type	: 	"POST",
		url		:	"../php/ajax_setunload.php",
		data	:	{
					instanceID: instanceID,
					fieldedits: fieldedits
					
					},
		success	:	function(sql) {
			//console.log(sql);

		}
	});
	});


});


