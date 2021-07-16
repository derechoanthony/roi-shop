jQuery(document).ready(function($) {
	
	var instanceID=$('#instanceID').val();
	//console.log('instanceID: ' + instanceID);
	

	$(window).bind('beforeunload', function(){
          //the custom message for page unload doesn't work on Firefox
          //alert("Goodbye!1");
         });


});
	
	$('.currency-change').blur(function(){
		//console.log('trying currency change');
		var currencysel = $(this).val();
		numeral.language(currencysel);

		//$('.calcx').calx('refresh');
		$('.calcx').calx('getSheet').update();
		$('.calcx').calx('getSheet').calculate();
		$(this).val(currencysel);
	});
	
	
	$('.roishop-wb-field').blur(function(){
		//Log value into database
		//Need to use an instanceID number
		
		//1. Get all values that have been given
		//   Loop through all fields with the class -- 'roishop-wb-field' 

		var form 		= $('#roiwebapp');
		var data 		= $("#roiwebapp" ).serialize();			
		var instanceID 	= $('#instanceID').val();	
		
		//console.log ('i:' + data);

		for(var i =0, len = data.length;i<len;i++){
		  //data[i] = data[i].trim();
		  data[i] = numeral().unformat(data[i]);
		  
		  
		}

		//console.log ('new:' + data);

		//2. Send the data to 'ajax_instancedata for saving to the database'
		//console.log ('try to post data');
		$.ajax({
		type	: 	"POST",
		url		:	"//www.theroishop.com/webapps/php/ajax_instancedata.php",
		data	:	'instanceID=' + instanceID + '&' + data,
		success	:	function(sql) {
			//console.log(sql);
			
			
    
			//alert(sql);
		}
	});
	});
	
	$('.calcx').calx({
		
		autoCalculateTrigger	:	'keyup',
		defaultFormat			:	'0,0[.]00',
		onAfterCalculate		:	function() {
			
			


		}
	});
	
	$(window).on("beforeunload",function() {
    	//console.log ('bye');
		});
	
	
	$(window).unload(function(){
		alert('unload');
		var instanceID 	= $('#instanceID').val();
		$.ajax({
		type	: 	"POST",
		url		:	"//www.theroishop.com/webapps/php/ajax_setunload.php",
		data	:	'instanceID=' + instanceID,
		success	:	function(sql) {
			//console.log(sql);

		}
	});
	});
