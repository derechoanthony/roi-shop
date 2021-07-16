



$(document).ready(function() {
	
	//Find Each Instance of a div element that has a class of ROICalcElem
	//Get the Text of that div which will correspond to the ROICalcElemID Number
	//Use that ID Number to do the correct creation

	buildChart();

	//Add the appropriate number of series to the form
	var numseries = $('#series_number').val();
	//if(numseries<1){numseries=1;}
	for(i=1;i<=numseries;i++){
		console.log(i);
		newSeries();
	}


function buildChart() {	
	$('.ROICalcElemID').each(function(){
		$(this).data('animate', "1");
		var ROIElemID = $(this).data("id");
		console.log ('trying to build chart: ' + ROIElemID);
		//PHP will determine if the object is a chart
		//May need to rename php file in next call.
        $.getJSON("../construct/charting/chartoptions.php?ROICalcElemID=" + ROIElemID, function(json) {
        
        	console.log('ChartOptions.php Output: ');
        	console.log("JSON: " + JSON.stringify(json));
	        console.log("Render to element with ID : " + json.chart.renderTo);
	        console.log("Number of matching dom elements : " + $("#" + json.chart.renderTo).length);

        	chart = new Highcharts.Chart( 
        		//json
        		json
        		);
        	
 
        });		//End getJSON function
	$(this).data('animate', "0");
	});			//End Each Div Class=ROICalcElem Loop
	

};

	function newSeries(){
		//This function adds a new series to the form
		
		//1. Get the current number of series
		var dbseriesnum = $('#series_number').val();
		var formseriesnum = $('#form_series_number').val();
		
		var nextnum = formseriesnum/1 + 1;
		
		var serieshtml = 	'<div class="form-group">';
        serieshtml = serieshtml + '<label class="col-sm-2 control-label smallbold">Series ' +  nextnum + '</label>';
        serieshtml = serieshtml + '<div class="col-sm-9">';
        serieshtml = serieshtml + '<div class="input-group"><input type="text" class="form-control" id="' + nextnum + 'seriesTitle"> <span class="input-group-btn">'; 
        serieshtml = serieshtml + '<a data-toggle="modal" class="btn btn-primary" href="#editSeries' +  nextnum + '">Edit</a>';
        serieshtml = serieshtml + '<a class="btn btn-danger" href="#deleteSeries1">Delete</a>';
        serieshtml = serieshtml + '</span></div>';
        serieshtml = serieshtml + '<!-- Begin Modal for Delete This Series -->';
                                         
        serieshtml = serieshtml + '<!-- End Modal for Delete This Series -->';
                                         
                                         
        serieshtml = serieshtml + '<!-- Begin Modal for Edit This Series -->';
        serieshtml = serieshtml + '<div id="editSeries' +  nextnum + '" class="modal fade" aria-hidden="true">';
        serieshtml = serieshtml + '<div class="modal-dialog">';
	    serieshtml = serieshtml + '<div class="modal-content">';
	    serieshtml = serieshtml + '<div class="modal-body">';
	    serieshtml = serieshtml + '<div class="row">';
		serieshtml = serieshtml + '<div class="col-lg-12"><h3 class="m-t-none m-b">Edit Series ' +  nextnum + '</h3>';
		serieshtml = serieshtml + '<br>';
		serieshtml = serieshtml + '<div class="form-group"><label class="col-lg-4 control-label smallbold">Y-Axis Values</label>';
		serieshtml = serieshtml + '<div class="col-sm-8"><select class="form-control m-b" name="account">';
		serieshtml = serieshtml + '<option>Section Totals</option>';
		serieshtml = serieshtml + '<option>Internal Views</option>';
		serieshtml = serieshtml + '<option>External Views</option>';
		serieshtml = serieshtml + '</select>';
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
	    serieshtml = serieshtml + '<div class="form-group"><label class="col-lg-4 control-label smallbold">Series Type</label>';
		serieshtml = serieshtml + '<div class="col-sm-8"><select class="form-control m-b updateseries" name="' +  nextnum + '-modalSeriesType" data-hidinput="' +  nextnum + '-seriestype">';
		serieshtml = serieshtml + '<option value="line">Line</option>';
		serieshtml = serieshtml + '<option value="column">Column</option>';
		serieshtml = serieshtml + '<option value="area">Area</option>';
		serieshtml = serieshtml + '</select>';
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
	    serieshtml = serieshtml + '<div class="form-group"><label class="col-lg-4 control-label smallbold">Series Title</label>';
		serieshtml = serieshtml + '<div class="col-lg-8"><input type="text" placeholder="Series Title" class="form-control" name="' +  nextnum + '-modalSeriesTitle"></div>';
		serieshtml = serieshtml + '</div>';
		
		
		
		serieshtml = serieshtml + '<div class="tabs-container">';
        serieshtml = serieshtml + '<ul class="nav nav-tabs">';
        serieshtml = serieshtml + '<li class="active"><a data-toggle="tab" href="#tab-series' + nextnum + '-colors">Colors</a></li>';
        serieshtml = serieshtml + '<li class=""><a data-toggle="tab" href="#tab-series' + nextnum + '-points">Point Styles</a></li>';
        serieshtml = serieshtml + '<li class=""><a data-toggle="tab" href="#tab-series' + nextnum + '-lines">Line Styles</a></li>';
        serieshtml = serieshtml + '<li class=""><a data-toggle="tab" href="#tab-series' + nextnum + '-labels">Data Labels</a></li>';
        serieshtml = serieshtml + '</ul>';
        serieshtml = serieshtml + '<div class="tab-content">';
        serieshtml = serieshtml + '<div id="tab-series' + nextnum + '-colors" class="tab-pane active">';
        serieshtml = serieshtml + '<div class="panel-body">';
        
        serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<label class="col-lg-4 control-label smallbold">Series Color</label>';
		serieshtml = serieshtml + '<div class="col-lg-2"><input type="text" placeholder="#FFFFFF" class="form-control colorpicker" name="' +  nextnum + '-modalSeriesAreaColor"></div>';
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<label class="col-lg-4 control-label smallbold">Transparency</label>';
		serieshtml = serieshtml + '<div class="col-lg-4"><input type="text" placeholder="50" class="form-control" name="' +  nextnum + '-modalSeriesAreaTrans"></div>';
		serieshtml = serieshtml + '</div>';

		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
		
		
		serieshtml = serieshtml + '<div id="tab-series' + nextnum + '-points" class="tab-pane">';
		serieshtml = serieshtml + '<div class="panel-body">';
		
		serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<label class="col-lg-4 control-label smallbold">Point Color</label>';
		serieshtml = serieshtml + '<div class="col-lg-8"><input type="text" placeholder="#FFFFFF" class="form-control colorpicker" name="' +  nextnum + '-modalseriesPointColor"></div>';
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<label class="col-lg-4 control-label smallbold">Point Symbol</label>';
		serieshtml = serieshtml + '<div class="col-sm-8">';
		serieshtml = serieshtml + '<select class="form-control m-b" name="' +  nextnum + '-modalseriesPointType">';
		serieshtml = serieshtml + '<option value="circle">Circle</option>';
		serieshtml = serieshtml + '<option value="square">Square</option>';
		serieshtml = serieshtml + '<option value="diamond">Diamond</option>';
		serieshtml = serieshtml + '<option value="triangle">Triangle</option>';
		serieshtml = serieshtml + '<option value="triangle-down">Inverted Triangle</option>';
		serieshtml = serieshtml + '</select>';
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<label class="col-lg-4 control-label smallbold">Size</label>';
		serieshtml = serieshtml + '<div class="col-sm-4">';
		serieshtml = serieshtml + '<select class="form-control m-b" name="' +  nextnum + '-modalseriesPointSize">';
		serieshtml = serieshtml + '<option value="0">0</option>';
		serieshtml = serieshtml + '<option value="1">1</option>';
		serieshtml = serieshtml + '<option value="2">2</option>';
		serieshtml = serieshtml + '<option value="3">3</option>';
		serieshtml = serieshtml + '<option value="4">4</option>';
		serieshtml = serieshtml + '<option value="5">5</option>';
		serieshtml = serieshtml + '<option value="6">6</option>';
		serieshtml = serieshtml + '<option value="7">7</option>';
		serieshtml = serieshtml + '<option value="8">8</option>';
		serieshtml = serieshtml + '<option value="9">9</option>';
		serieshtml = serieshtml + '<option value="10">10</option>';
		serieshtml = serieshtml + '</select>';
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
		
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
		
		serieshtml = serieshtml + '<div id="tab-series' + nextnum + '-lines" class="tab-pane">';
        serieshtml = serieshtml + '<div class="panel-body">';
        
		serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<label class="col-lg-4 control-label smallbold">Line Color</label>';
		serieshtml = serieshtml + '<div class="col-lg-8"><input type="text" placeholder="#FFFFFF" class="form-control colorpicker" name="' +  nextnum + '-modalseriesLineColor"></div>';
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '<div class="form-group"><label class="col-lg-4 control-label smallbold">Line Style</label>';
		serieshtml = serieshtml + '<div class="col-sm-8">';
		serieshtml = serieshtml + '<select class="form-control m-b" name="' +  nextnum + '-modalseriesLineStyle">';
		serieshtml = serieshtml + '<option value="Solid">Solid</option>';
		serieshtml = serieshtml + '<option value="ShortDash">Short Dash</option>'; 
		serieshtml = serieshtml + '<option value="ShortDot">Short Dot</option>'; 
		serieshtml = serieshtml + '<option value="ShortDashDot">Short Dash Dot</option>'; 
		serieshtml = serieshtml + '<option value="ShortDashDotDot">Short Dash Dot Dot</option>'; 
		serieshtml = serieshtml + '<option value="Dot">Dotted</option>'; 
		serieshtml = serieshtml + '<option value="Dash">Dashed</option>'; 
		serieshtml = serieshtml + '<option value="LongDash">Long Dash</option>'; 
		serieshtml = serieshtml + '<option value="DashDot">Dash Dot</option>'; 
		serieshtml = serieshtml + '<option value="LongDashDot">Long Dash Dot</option>'; 
		serieshtml = serieshtml + '<option value="LongDashDotDot">Long Dash Dot Dot</option>'; 
		serieshtml = serieshtml + '</select>';
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
		
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
		
		serieshtml = serieshtml + '<div id="tab-series' + nextnum + '-labels" class="tab-pane">';
        serieshtml = serieshtml + '<div class="panel-body">';
        
        serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<div class="col-lg-4"><label>Enabled</label></div>';
	    serieshtml = serieshtml + '<div class="col-lg-4"><select class="form-control m-b" name="' +  nextnum + '-modaldatalabelEnabled">';
		serieshtml = serieshtml + '<option value="1">Enabled</option>';
		serieshtml = serieshtml + '<option value="0">Disabled</option>'; 
		serieshtml = serieshtml + '</select></div>';  
		serieshtml = serieshtml + '</div>';
		
		serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<div class="col-lg-4"><label> Prefix </label></div>';
		serieshtml = serieshtml + '<div class="col-lg-4"><label> Value </label></div>';
		serieshtml = serieshtml + '<div class="col-lg-4"><label> Suffix </label></div>';
		//serieshtml = serieshtml + '</div>';
		
		//serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<div class="col-lg-4"><input type="text" placeholder="Prefix" name="' +  nextnum + '-modaldatalabelPrefix" value="" class="form-control SaveChartOpts"></div>'; 
		serieshtml = serieshtml + '<div class="col-lg-4"><select class="form-control m-b" name="' +  nextnum + '-modaldatalabelValue">';
		serieshtml = serieshtml + '<option value="{y}">y Value</option>';
		serieshtml = serieshtml + '<option value="{x}">x Value</option>'; 
		serieshtml = serieshtml + '</select></div>';
		serieshtml = serieshtml + '<div class="col-lg-4"><input type="text" placeholder="Suffix" name="' +  nextnum + '-modaldatalabelSuffix" value="" class="form-control SaveChartOpts"></div>'; 
		serieshtml = serieshtml + '</div>';
		
		
		serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '</div>';
		
		serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<div class="col-lg-4"><label>Font</label></div>';
	    serieshtml = serieshtml + '<div class="col-lg-4"><select class="form-control m-b" name="' +  nextnum + '-modaldatalabelFontID">';
		serieshtml = serieshtml + '<option value="5">Arial</option>';
		serieshtml = serieshtml + '<option value="6">Arial Black</option>';
		serieshtml = serieshtml + '<option value="2">Comic Sans</option>';
		serieshtml = serieshtml + '<option value="12">Courier</option>';
		serieshtml = serieshtml + '<option value="11">Geneva</option>';
		serieshtml = serieshtml + '<option value="1">Georgia</option>';
		serieshtml = serieshtml + '<option value="10">Helvetica</option>';
		serieshtml = serieshtml + '<option value="7">Impact</option>';
		serieshtml = serieshtml + '<option value="8">Lucida Grande</option>';
		serieshtml = serieshtml + '<option value="13">Monaco</option>';
		serieshtml = serieshtml + '<option value="3">Palatino</option>';
		serieshtml = serieshtml + '<option value="9">Tahoma</option>';
		serieshtml = serieshtml + '<option value="4">Times New Roman</option>'; 
		serieshtml = serieshtml + '</select></div>';                          
		serieshtml = serieshtml + '</div>';
		
		serieshtml = serieshtml + '<div class="form-group"><label class="col-lg-4 control-label smallbold">Font Size</label>';
		serieshtml = serieshtml + '<div class="col-lg-4">';
		serieshtml = serieshtml + '<select class="form-control m-b" name="' +  nextnum + '-modaldatalabelFontSize">';
		serieshtml = serieshtml + '<option value="8">8 px</option>';
		serieshtml = serieshtml + '<option value="9">9 px</option>';
		serieshtml = serieshtml + '<option value="10">10 px</option>';
		serieshtml = serieshtml + '<option value="11">11 px</option>';
		serieshtml = serieshtml + '<option value="12">12 px</option>';
		serieshtml = serieshtml + '<option value="13">13 px</option>';
		serieshtml = serieshtml + '<option value="14">14 px</option>';
		serieshtml = serieshtml + '<option value="15">15 px</option>';
		serieshtml = serieshtml + '<option value="16">16 px</option>';
		serieshtml = serieshtml + '<option value="17">17 px</option>';
		serieshtml = serieshtml + '<option value="18">18 px</option>'; 
		serieshtml = serieshtml + '</select>';
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
		
		serieshtml = serieshtml + '<div class="form-group">';
		serieshtml = serieshtml + '<div class="col-lg-4"><label>Font Color</label></div>';
	    serieshtml = serieshtml + '<div class="col-lg-4"><input type="text" placeholder="Color" name="' +  nextnum + '-modaldatalabelFontColor" value="" class="form-control colorpicker SaveChartOpts"></div>';                          
		serieshtml = serieshtml + '</div>';
		
		//serieshtml = serieshtml + '<div class="form-group">';
		//serieshtml = serieshtml + '<div class="col-lg-4"><label> Suffix </label></div>';
	    //serieshtml = serieshtml + '<div class="col-lg-4">';
		//serieshtml = serieshtml + '<input type="text" placeholder="Suffix" name="' +  nextnum + '-modalseriesDLSuffix" value="" class="form-control SaveChartOpts"></div>';                          
		//serieshtml = serieshtml + '</div>';
		
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
		
		
		
		serieshtml = serieshtml + '</div>';
		serieshtml = serieshtml + '</div>';
		
		
		serieshtml = serieshtml + '<br><br>';
		
		
		
		
		
		
		
		
		
		
		

		
		serieshtml = serieshtml + '<button type="submit" class="btn btn-primary pull-left SaveChartOpts" data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span> DONE</button>';
                                
	    serieshtml = serieshtml + '</div> <!-- End Coloumn -->';
	    serieshtml = serieshtml + '</div> <!-- End Row -->';
	    serieshtml = serieshtml + '</div> <!-- End Modal Body -->';
	    serieshtml = serieshtml + '</div> <!-- End Modal Content -->';
        serieshtml = serieshtml + '</div> <!-- End Modal Dialog -->'; 
        serieshtml = serieshtml + '</div>	<!-- End editSeries1 -->';
        serieshtml = serieshtml + '<!-- End Modal for Edit This Series -->';
        serieshtml = serieshtml + '</div>';
        serieshtml = serieshtml + '</div>';
        
		$('#SeriesInfo').append(serieshtml);
		$('#form_series_number').val(nextnum);
			
		//see if there are hidden form fields for the newly created series modal
		//if so add the replace the values in the modal with the values stored in the hidden inputs from pho
		
		if ($("input[name=" + nextnum + "-seriestype]").length>0) {
			var seriestype = $("input[name=" + nextnum + "-seriestype]").val();
		   	$("select[name=" + nextnum + "-modalSeriesType]").val(seriestype);
			var seriestitle = $("input[name=" + nextnum + "-seriestitle]").val();
		   	$("input[name=" + nextnum + "-modalSeriesTitle]").val(seriestitle);
		   	var seriesareacolor = $("input[name=" + nextnum + "-seriesareacolor]").val();
		   	$("input[name=" + nextnum + "-modalSeriesAreaColor]").val(seriesareacolor);
		   	var seriesareatrans = $("input[name=" + nextnum + "-seriesareatrans]").val();
		   	$("input[name=" + nextnum + "-modalSeriesAreaTrans]").val(seriesareatrans);
		   	var seriespointcolor = $("input[name=" + nextnum + "-seriespointcolor]").val();
		   	$("input[name=" + nextnum + "-modalseriesPointColor]").val(seriespointcolor);
		   	var seriespointsymbol = $("input[name=" + nextnum + "-seriespointsymbol]").val();
		   	$("select[name=" + nextnum + "-modalseriesPointType]").val(seriespointsymbol);
		   	
		   	var serieslinecolor = $("input[name=" + nextnum + "-serieslinecolor]").val();
		   	$("input[name=" + nextnum + "-modalseriesLineColor]").val(serieslinecolor);
		   	var serieslinestyle = $("input[name=" + nextnum + "-serieslinestyle]").val();
		   	$("select[name=" + nextnum + "-modalseriesLineStyle]").val(serieslinestyle);
		   	
		   	var seriespointsize = $("input[name=" + nextnum + "-seriespointsize]").val();
		   	$("select[name=" + nextnum + "-modalseriesPointSize]").val(seriespointsize);
		   	
		   	var datalabelEnabled = $("input[name=" + nextnum + "-datalabelEnabled]").val();
		   	$("select[name=" + nextnum + "-modaldatalabelEnabled]").val(datalabelEnabled);
		   	
		   	var datalabelPrefix = $("input[name=" + nextnum + "-datalabelPrefix]").val();
		   	$("input[name=" + nextnum + "-modaldatalabelPrefix]").val(datalabelPrefix);
		   	var datalabelValue = $("input[name=" + nextnum + "-datalabelValue]").val();
		   	$("select[name=" + nextnum + "-modaldatalabelValue]").val(datalabelValue);
		   	var datalabelSuffix = $("input[name=" + nextnum + "-datalabelSuffix]").val();
		   	$("input[name=" + nextnum + "-modaldatalabelSuffix]").val(datalabelSuffix);
		   	
		   	var datalabelFontID = $("input[name=" + nextnum + "-datalabelFontID]").val();
		   	$("select[name=" + nextnum + "-modaldatalabelFontID]").val(datalabelFontID);
		   	var datalabelFontSize = $("input[name=" + nextnum + "-datalabelFontSize]").val();
		   	$("select[name=" + nextnum + "-modaldatalabelFontSize]").val(datalabelFontSize);
		   	var datalabelFontColor = $("input[name=" + nextnum + "-datalabelFontColor]").val();
		   	$("input[name=" + nextnum + "-modaldatalabelFontColor]").val(datalabelFontColor);
		}
		
		
		//else create the hidden fields and append to that div
	var seriesinputs = 				  '<input type="hidden" name="' + nextnum + '-seriestype" 			value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-seriestitle" 			value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-seriesareacolor" 		value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-seriesareatrans" 		value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-seriespointcolor" 	value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-seriespointsymbol" 	value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-serieslinecolor" 		value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-serieslinestyle" 		value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-seriespointsize" 		value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-datalabelEnabled" 	value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-datalabelPrefix" 		value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-datalabelValue" 		value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-datalabelSuffix" 		value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-datalabelFontID" 		value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-datalabelFontColor" 	value="" />';
		seriesinputs = seriesinputs + '<input type="hidden" name="' + nextnum + '-datalabelFontSize" 	value="" />';
		$('#serieshiddeninfo').append(seriesinputs);	
				
	};//End Add Series to chart builder function


	$('.newSeries').click( function() {
		newSeries();
	});

 
});				//End Document Ready Function
 

	function getseriesdata(){
		var formseriesnum = $('#form_series_number').val();
		for(i=1; i<=formseriesnum; i++){
			var seriestype = $("select[name=" + i + "-modalSeriesType]").val();
			$("input[name=" + i + "-seriestype]").val(seriestype);
			var seriestitle = $("input[name=" + i + "-modalSeriesTitle]").val();
			$("input[name=" + i + "-seriestitle]").val(seriestitle);
			var seriesareacolor = $("input[name=" + i + "-modalSeriesAreaColor]").val();
			$("input[name=" + i + "-seriesareacolor]").val(seriesareacolor);
			var seriesareatrans = $("input[name=" + i + "-modalSeriesAreaTrans]").val();
			$("input[name=" + i + "-seriesareatrans]").val(seriesareatrans);
			var seriespointcolor = $("input[name=" + i + "-modalseriesPointColor]").val();
			$("input[name=" + i + "-seriespointcolor]").val(seriespointcolor);
			var seriespointsymbol = $("select[name=" + i + "-modalseriesPointType]").val();
		   	$("input[name=" + i + "-seriespointsymbol]").val(seriespointsymbol);
		   	
		   	var serieslinecolor = $("input[name=" + i + "-modalseriesLineColor]").val();
			$("input[name=" + i + "-serieslinecolor]").val(serieslinecolor);
			var serieslinestyle = $("select[name=" + i + "-modalseriesLineStyle]").val();
		   	$("input[name=" + i + "-serieslinestyle]").val(serieslinestyle);
		   	
		   	var seriespointsize = $("select[name=" + i + "-modalseriesPointSize]").val();
		   	$("input[name=" + i + "-seriespointsize]").val(seriespointsize);
		   	
		   	var datalabelEnabled = $("select[name=" + i + "-modaldatalabelEnabled]").val();
		   	$("input[name=" + i + "-datalabelEnabled]").val(datalabelEnabled);
		   	
		   	var datalabelPrefix = $("input[name=" + i + "-modaldatalabelPrefix]").val();
		   	$("input[name=" + i + "-datalabelPrefix]").val(datalabelPrefix);
		   	var datalabelValue = $("select[name=" + i + "-modaldatalabelValue]").val();
		   	$("input[name=" + i + "-datalabelValue]").val(datalabelValue);
		   	var datalabelSuffix = $("input[name=" + i + "-modaldatalabelSuffix]").val();
		   	$("input[name=" + i + "-datalabelSuffix]").val(datalabelSuffix);
		   	
		   	var datalabelFontID = $("select[name=" + i + "-modaldatalabelFontID]").val();
		   	$("input[name=" + i + "-datalabelFontID]").val(datalabelFontID);
		   	var datalabelFontSize = $("select[name=" + i + "-modaldatalabelFontSize]").val();
		   	$("input[name=" + i + "-datalabelFontSize]").val(datalabelFontSize);
		   	var datalabelFontColor = $("input[name=" + i + "-modaldatalabelFontColor]").val();
		   	$("input[name=" + i + "-datalabelFontColor]").val(datalabelFontColor);
		   
		};
		
	};


	$('.fontstyles').click(function() {
		//console.log ('trying subtitle font dialoge');
		var textlocation = $(this).data("fontlocation");
		//Construct the html for the font dialog box
		
		var modalID = $(this).data("modalID");
		
		var modalhtml  = 	'<div class="modal-dialog">';
	        modalhtml +=	'<div class="modal-content">';
            modalhtml +=	'<div class="modal-body">';
            modalhtml +=	'     <div class="row">';
            modalhtml +=	'     <div class="col-lg-12"><h3 class="m-t-none m-b">' + textlocation + ' Font Styles</h3>';
            modalhtml +=	'     </div>';
            modalhtml +=	'     </div>';
            modalhtml +=	'</div>';
            modalhtml +=	'</div>';
            modalhtml +=	'</div>';
                             
        $('#' + modalID).innerHTML=modalhtml;
                                            
		//console.log (modalhtml);
	});



		//Save the data from the modals to the form via hidden fields
		$('.modal-data').on('focusout', function() {
			var newvalue 		= $(this).val();
			var modalfieldname	= $(this).attr("name");
			var formfieldname 	= modalfieldname.replace("modal_","");
			$("input[name=" + formfieldname + "]").val(newvalue);
		});
		
		//Need to handle the ichecks separately
		$('.i-checks').on('ifChecked', function(){
			var checkedval 		= 1;
			var inputname 		= $(this).data('hiddeninput');
			$("input[name=" + inputname + "]").val(checkedval);
			console.log('got checked');
		});
		
		$('.i-checks').on('ifUnchecked', function(){
			var checkedval 		= 0;
			var inputname 		= $(this).data('hiddeninput');
			$("input[name=" + inputname + "]").val(checkedval);
			console.log('got unchecked');
		});
		
		
	
		$('.SaveChartOpts').on( 'click', function() {
		getseriesdata();		
		console.log('trying to save new chart parameters to database: .SaveChartOpts in bildchart.js calling to database.manipulation.php');
		var form 		= $('#formChartOpts');
		var data 		= $( "#formChartOpts" ).serialize();
		var chartID 	= $('#chartID').val();	
		console.log ('trying to update chart' + chartID);	
		console.log (data);
		$.ajax({
			type	: 	"POST",
			url		:	"../construct/charting/elements/charts.storeoptions.php",
			data	:	'action=storeChartOpts&chartID='+ chartID + '&' + data,
			success	:	function(sql) {
				console.log('Updated Chart Options!');
				//console.log(sql);
				var chart=$("#ROICalcElemID1").highcharts();
				console.log('original options');
				console.log(chart.options);
				$.getJSON("../construct/charting/chartoptions.php?ROICalcElemID="+ chartID, function(json) {
        
        	json.plotOptions.series["animation"]=false;
        
        	console.log('ChartOptions.php Output: ');
        	console.log("JSON: " + JSON.stringify(json));
	        console.log("Render to element with ID : " + json.chart.renderTo);
	        console.log("Number of matching dom elements : " + $("#" + json.chart.renderTo).length);
			chart.destroy();
			chart = new Highcharts.Chart(json);
        	chart.render();
 
        });		//End getJSON function
        
				//alert(sql);
			}
		});
	});
 
    
