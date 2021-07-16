
var xhr = null;
var xhr1 = null;

function sendformdata () {
      
      	//console.log ('starting send form data');
      
      	if (xhr != null) {
      		
      		xhr.abort();
      		xhr=null;
      		//console.log ('aborted xhr');
      		
      	}
      	
      	if (xhr1 != null) {
      		
      		xhr1.abort();
      		xhr1=null;
      		//console.log ('aborted xhr1');
      		
      	}
      
      
      
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
		
			xhr = $.ajax({
			type		: 	"POST",
			url			:	"../php/ajax_instancedata_v1-5.php",
			data		:	{
							instanceID: instanceID,
							roicalcID: roicalcID,
							roicalcstatus: roicalcstatus,
							data_formatted: data,
							data_unformatted: data_unformatted
							},
		    success		:	function(sql) {
			//console.log(sql);
						
		}
			
		});
		
		/*
		
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
	
	//*/
	
		//Send standard field data in case browser is closed early
		var instanceID 	= $('#instanceID').val();
		var fieldedits 	= $('#fieldedits').val();
		//console.log ('field edits: ' + fieldedits);
		
		/*
			xhr1 = jQuery.ajax({
			type		: 	"POST",
			url			:	"../php/ajax_setunload.php",
			data		:	{
							instanceID: instanceID,
							fieldedits: fieldedits
							},
		    success		:	function(sql) {
			//console.log(sql);
						
		}
			
		});
		///*
		/*
		
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
	
	//*/


	

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
      
      //console.log ('starting ajax' + $.now());
      $.ajax({		
			type	: 	'POST',
			url		: 	'../macros/runmacro-v10.php',
			data	:	{ 
						instanceid: instanceid, 
						elementid: elementid
						},
			success	:	function(response) {
			  //On return look through the echo return to 
              //perform any necessary jquery commands  

			  console.log(JSON.parse(response));

			

              var responseout = JSON.parse(response);
			  console.log ('responseout: ' + JSON.stringify(response));
              var nextaction = responseout['nextaction'];
			  console.log ('nextaction: ' + nextaction);
			  if(responseout['errorlog']==1){nextaction=0;}
			  
			  //0 - Just do a console.log
              //1 - create alert
              //2 - direct to another location
			  //3 - Open Modal window
			  
			  //console.log('all macro outputs: ');
              //console.log (JSON.parse(response));
			  
              //switch(nextaction)
              switch(nextaction) {
              	 case 0:
              	 	//Error finding;
              	 	console.log('all macro outputs: ');
              	 	console.log (JSON.parse(response));
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
                  	 //console.log (JSON.parse(response));
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
                  
                  case 5:
                  //5
                  //Rediect to the reflect page with a specific instanceID and reportID
                  var reportID=responseout['reportid'];
                  var instanceID = responseout['instanceid'];
                  var wbappID = responseout['wbappid'];
                  
                  var address ='http://www.theroishop.com/webapps/icalc/reflect.php?wbappID=' + wbappID + '&r=' + reportID + '&i=' + instanceID;
                  //console.log ('address: ' + address);
                  
                  window.top.location.href = address;
                  break;
                  
                  default:
                      //Nothing.
                      
                     
              }
              
              //*/	
			}
			
			
			
		});
		
		//console.log('finally done');
}

jQuery(document).ready(function($) {
	
	var instanceID=$('#instanceID').val();
	var curfocusval = '';
	
	

	
	$('.currency-change').blur(function(){
		console.log ('currency change');
		var currencysel = $(this).val();
		numeral.language(currencysel);
		console.log ('updating');
		$('.calcx').calx('getSheet').update();
		$('.calcx').calx('getSheet').calculate();
		$(this).val(currencysel);
		console.log ('finish currency');
	});
	
	$('.closemodal').on('click', function(){
    	$('#macromodal').modal('hide');  
   	});
	
	$('.roimacro').on('click', function(){
    	runmacro($(this));  
   	});
	 
	
	$('#modalholder').on('blur', '.roishop-wb-field', function(){
		console.log ('wbfieldmodal');
    	sendformdata();
	});
	
	$('#modalholder').on('blur', '.required', function(){
		console.log ('required modal');
		var nogo = 0;
    	$('.required').each(function(){
    		if($(this).val()=='' || $(this).val()==null){nogo=nogo + 1;}
    	});
    	
    	if(nogo==0){
    		$('#hiddenrequired').removeClass('requiredhidden');
    		$('#nothiddenrequired').addClass('requiredhidden');
    		
    	} else {
    		$('#nothiddenrequired').removeClass('requiredhidden');
    		$('#hiddenrequired').addClass('requiredhidden');
    		
    	}
    	
	});
	
	$('#modalholder').on('click', '.roimacro', function(){
    	runmacro($(this));
	});
	
	$('.roishop-wb-field').blur(function(){
		console.log ('blur wbfield');
		sendformdata();
	});
	
	
	$('.roishop-actiontracked').focus(function(){
		console.log ('action tracked');
		curfocusval = $(this).val();
	});
	
	
	$('.roishop-actiontracked').blur(function(){
		console.log ('action tracked blur');
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
		autoCalculateTrigger	:	'blur',
		defaultFormat			:	'0,0[.]00',
		onAfterCalculate		:	function() {
		}
	});
	
	
	$(window).unload(function(){
		//alert('unload');
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


