
<?php include '../inc/analyticshead.php'; 
$roiID = $_GET['wbappID'];
$key 	= $_GET['key'];

if (isset($_GET["r"])) {
    $r = $_GET['r'];    
}else{  
    $r = 3;
}

$tabname='dashboard';

$wbappID = $roiID;


?>


<style>
.nav-tabs {
	border-bottom: 0px;
}

.fixed_header{
    width: 500px;
    table-layout: fixed;
    border-collapse: collapse;
}

.fixed_header tbody{
  display:block;
  width: 100%;
  overflow: auto;
  height: 400px;
}

.fixed_header thead tr {
   display: block;
}

.fixed_header thead {
  //background: black;
  //color:#fff;
}

.fixed_header th, .fixed_header td {
  //padding: 5px;
  //text-align: left;
}
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Analytics Report for</h2>
        <ol class="breadcrumb">           
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

<div class="row">
	<div class="col-sm-12">
		<h4>Show Results For Past:</h4>
		<div class="btn-group">
			<button class="btn btn-white intervalchange" type="button"  data-interval="1">Week</button>
			<button class="btn btn-white intervalchange" type="button"  data-interval="2">2 Weeks</button>
			<button class="btn btn-primary intervalchange" type="button" data-interval="3">Month</button>
			<button class="btn btn-white intervalchange" type="button" data-interval="4">2 Months</button>
			<button class="btn btn-white intervalchange" type="button" data-interval="5">3 Months</button>
			<button class="btn btn-white intervalchange" type="button" data-interval="6">6 Months</button>
			<button class="btn btn-white intervalchange" type="button" data-interval="7">Year</button>
			<button class="btn btn-white intervalchange" type="button" data-interval="8">All Time</button>
		</div>
		<hr>
	</div>
	
</div>

    	<input type="hidden" id="roiID" value="<?php echo $roiID; ?>"></input>
 
		
 
</div>

</div>


<div class="row">

        <div class="col-lg-12">
        <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>All Instance Data <small>Viewing Data for the last three months.</small></h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><btn data-toggle="modal" data-target="#modalmoredetails">Edit Table Columns</a>
                    </li>
                    <li><a href="#">Config option 2</a>
                    </li>
                </ul>

            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-sm-9 m-b-xs">
				
                    <div data-toggle="buttons" class="btn-group">
                        <label class="btn btn-sm btn-white"> <input type="radio" id="option1" name="options"> All  </label>
                        <label class="btn btn-sm btn-white active"> <input type="radio" id="option2" name="options"> Production </label>
                        <label class="btn btn-sm btn-white"> <input type="radio" id="option3" name="options"> Testing </label>
                    </div>
                </div>
                
            </div>
            <table class="table table-striped table-bordered table-hover dataTables-example" >
			<thead>
			
				
				<div id="headerlist">
				
				</div>
			
			</thead>
			<tbody id="allinstancedatatable">
			
			</tfoot>
			</table>
		
		<!-- More details modal -->
		
		<div class="modal inmodal fade" id="modalmoredetails" tabindex="-1" role="dialog"  aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title">More Details - Instance <span id="mdinstanceID"></span></h4>
						
					</div>
					<div class="modal-body">
						<div class="panel blank-panel">

                        <div class="panel-heading" style="margin-bottom: 0px;">
                            
                            <div class="panel-options" style="margin-bottom: 0px;">

                                <ul class="nav nav-tabs" style="margin-bottom: 0px;">
                                    <li class="active"><a data-toggle="tab" href="#tab-1">Lead Generated Responses</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab-2">Calculated Results</a></li>
									<li class=""><a data-toggle="tab" href="#tab-3">Contact Information</a></li>
									<li class=""><a data-toggle="tab" href="#tab-4">View Statistics</a></li>
									<li class=""><a data-toggle="tab" href="#tab-5">Notes</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body" style="background-color: #FFFFFF; border: solid 1px #dddddd; border-radius: 4px;">

                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane active">
                                    
									<div class="ibox float-e-margins">
                                    
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins">
                                            <thead>
                                            <tr>
                                                <th>Input Field</th>
                                                <th>User Generated Value</th>
                                                <th>Three Month Average Value</th>
                                            </tr>
                                            </thead>
                                            <tbody id="inputfieldstable">
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
									
									
									
                                </div>

                                <div id="tab-2" class="tab-pane">
                                    <div class="ibox float-e-margins">
                                    
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins fixed_header">
                                            <thead>
                                            <tr>
                                                <th>Calculated Field</th>
                                                <th>User Generated Value</th>
                                                <th>Three Month Average Value</th>
                                            </tr>
                                            </thead>
                                            <tbody id="calcfieldstable" style="max-height: 500px;">
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                </div>
								
								<div id="tab-3" class="tab-pane">
                                    <div class="ibox float-e-margins">
                                    
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins">
                                            <thead>
                                            <tr>
                                                <th>Contact Field</th>
                                                <th>User Generated Value</th>
                                            </tr>
                                            </thead>
                                            <tbody id="contactfieldstable">
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                </div>
								
                            </div>

                        </div>

                    </div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div>
		
		<!-- end More Details Modal -->


	<!-- More details modal -->
		
		<div class="modal inmodal fade" id="modaltablecolumns" tabindex="-1" role="dialog"  aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title">Edit Table Columns</h4>
						
					</div>
					<div class="modal-body">
						<div class="panel blank-panel">

                        <div class="panel-heading" style="margin-bottom: 0px;">
                            
                            <div class="panel-options" style="margin-bottom: 0px;">

                                <ul class="nav nav-tabs" style="margin-bottom: 0px;">
                                    <li class="active"><a data-toggle="tab" href="#tab-1">Lead Generated Responses</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab-2">Calculated Results</a></li>
									<li class=""><a data-toggle="tab" href="#tab-3">Contact Information</a></li>
									<li class=""><a data-toggle="tab" href="#tab-4">View Statistics</a></li>
									<li class=""><a data-toggle="tab" href="#tab-5">Notes</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body" style="background-color: #FFFFFF; border: solid 1px #dddddd; border-radius: 4px;">

                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane active">
                                    
									<div class="ibox float-e-margins">
                                    
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins">
                                            <thead>
                                            <tr>
                                                <th>Input Field</th>
                                                <th>User Generated Value</th>
                                                <th>Three Month Average Value</th>
                                            </tr>
                                            </thead>
                                            <tbody id="inputfieldstable">
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
									
									
									
                                </div>

                                <div id="tab-2" class="tab-pane">
                                    <div class="ibox float-e-margins">
                                    
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins fixed_header">
                                            <thead>
                                            <tr>
                                                <th>Calculated Field</th>
                                                <th>User Generated Value</th>
                                                <th>Three Month Average Value</th>
                                            </tr>
                                            </thead>
                                            <tbody id="calcfieldstable" style="max-height: 500px;">
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                </div>
								
								<div id="tab-3" class="tab-pane">
                                    <div class="ibox float-e-margins">
                                    
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins">
                                            <thead>
                                            <tr>
                                                <th>Contact Field</th>
                                                <th>User Generated Value</th>
                                            </tr>
                                            </thead>
                                            <tbody id="contactfieldstable">
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                </div>
								
                            </div>

                        </div>

                    </div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div>
		
		<!-- end More Details Modal -->


        </div>
        </div>
        </div>

        </div>
            
        </div>

<?php include '../inc/footer.php';?>

    <!-- CodeMirror -->
    <script src="../assets/js/plugins/codemirror/codemirror.js"></script>
    <script src="../assets/js/plugins/codemirror/mode/javascript/javascript.js"></script>


	<!-- Data Tables -->
    <script src="../assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="../assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="../assets/js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="../assets/js/plugins/dataTables/dataTables.tableTools.min.js"></script>
	<script src="../assets/js/underscore.js"></script>

</body>

</html>



<script>

function getviews(roiid) {
	//Get the view data from ajax call
	$.ajax({
	  type: 'POST',
	  url: 'ajax_getviews.php',
	  data: 'roiid=' + roiid,
	  cache: false,
	  success: function(data) {
		//console.log (data);
		viewdata = JSON.parse(data);
		getvalues(roiid)
	  },
	  });
	
}

function getvalues(roiid) {
	//Get the view data from ajax call
	$.ajax({
	  type: 'POST',
	  url: 'ajax_getvalues.php',
	  data: 'roiid=' + roiid,
	  cache: false,
	  success: function(data) {
		console.log (data);
		valuedata = JSON.parse(data);
		getstdvalues (roiid);
	  },
	  });
	
}

function getstdvalues(roiid) {
	//Get the view data from ajax call
	console.log (roiid);
	$.ajax({
	  type: 'POST',
	  url: 'ajax_getstdvalues.php',
	  data: 'roiid=' + roiid,
	  cache: false,
	  success: function(data) {
		console.log (data.toString());
		valuestddata = JSON.parse(data);
		getcalcfields(roiid);
	  },
	  });
	
}

function gettablecols(roiid) {
	//Get the columns to be listed in the table
	$.ajax({
	  type: 'POST',
	  url: 'ajax_gettablecols.php',
	  data: 'roiid=' + roiid,
	  cache: false,
	  success: function(data) {
		tablecols = JSON.parse(data);
		tablecolsstring = JSON.stringify(tablecols);
		console.log ('table cols: ' + tablecolsstring);
		getallinstancetabledata_cont(timeperiodselected);
	  },
	  });
	
}

function arrayAverage(arr) {
	var sumval = 0;
	var size = _.size(arr);

	_.each(arr, function(val, index, arr){
		sumval = sumval + arr[index]/1;
	});
	
	var avgval = sumval / size;
	return avgval;

    }

function arrayAverage1(arr) {
      return _.reduce(arr, function(memo, num) {
        return memo + num;
      }, 0) / (arr.length === 0 ? 1 : arr.length);
    }

function getaverages() {
	//Get averages
		var allstarted = _.where(_.flatten(alldata),{started:'Yes'});
		var timeperiodarray = [1,2,3,4,5,6,7,8];
		
		var fieldaverages = [];
		
		_.each(timeperiodarray, function(timeperiod, index, timeperiodarray){
			//console.log (optionsarray[index]);
			
			var filtered = _.filter(allstarted, function(item) {
				 return item.lapsecategorynum <= timeperiod
			});
			console.log ('filtered');
			console.log(filtered);
			//Loop through each field for this calculator
			_.each(calcfields, function(calcfield, index, calcfields){
				//console.log (optionsarray[index]);
				console.log (calcfield['fieldID']);
				
				var fieldinfo = {};
				
				fieldinfo['fieldID'] = calcfield['fieldID'];
				
				var fieldvalues = _.pluck(_.pluck(filtered,'values'),calcfield['fieldID']);
				console.log ('fieldvalues');
				console.log(fieldvalues);
				
				var fieldavgarray = {};
				
				fieldavgarray['min'] = _.min(fieldvalues)/1;
				fieldavgarray['max'] = _.max(fieldvalues)/1;
				fieldavgarray['size'] = _.size(fieldvalues);
				fieldavgarray['average'] = arrayAverage(fieldvalues);
				fieldinfo['statistics'] = fieldavgarray;
				
			
				console.log (fieldinfo);
				
				
			


			});
			
			
			
			
		});
	
}


function getcalcfields(roiid) {
	//Get the view data from ajax call
	$.ajax({
	  type: 'POST',
	  url: 'ajax_getcalcfields.php',
	  data: 'roiid=' + roiid,
	  cache: false,
	  success: function(data) {
		console.log (data);
		calcfields = JSON.parse(data);
		
		var inputtablehtml = '';
	
		var inputarray = _.where(_.flatten(calcfields),{fieldType: '1'});
		_.each(inputarray, function(fieldinfo, index, inputarray){
			inputtablehtml = inputtablehtml + '<tr><td>' + fieldinfo['shortName'] + '</td><td><span class="getinstancevalue" data-fieldid="' + fieldinfo['fieldID'] + '" id="fieldvalue_' + fieldinfo['fieldID'] + '"</span></td><td><span id="avgvalue_' + fieldinfo['fieldID'] + '"</span></td></tr>';
		});
		
		$('#inputfieldstable').html(inputtablehtml);
		
		var calctablehtml = '';
		var calcarray = _.where(_.flatten(calcfields),{fieldType: '2'});
		_.each(calcarray, function(fieldinfo, index, calcarray){
			calctablehtml = calctablehtml + '<tr><td>' + fieldinfo['shortName'] + '</td><td><span class="getinstancevalue" data-fieldid="' + fieldinfo['fieldID'] + '" id="fieldvalue_' + fieldinfo['fieldID'] + '"</span></td><td><span id="avgvalue_' + fieldinfo['fieldID'] + '"</span></td></tr>';
		});
		
		$('#calcfieldstable').html(calctablehtml);
		
		
		var contacttablehtml = '';
		var contactarray = _.where(_.flatten(calcfields),{fieldType: '100'});
		_.each(contactarray, function(fieldinfo, index, contactarray){
			contacttablehtml = contacttablehtml + '<tr><td>' + fieldinfo['shortName'] + '</td><td><span class="getinstancevalue" data-fieldid="' + fieldinfo['fieldID'] + '" id="fieldvalue_' + fieldinfo['fieldID'] + '"</span></td></tr>';
		});
		
		$('#contactfieldstable').html(contacttablehtml);
		
		
		
		
		
		
		formatdata (viewdata,valuedata, valuestddata);
	  },
	  });
	
	
	
}

function getDate(str) {
	
	var splitstr = str.split(" ");
	var splitstrdatepart = splitstr[0];
	var splitdatestr = splitstrdatepart.split("-");
	
	var returndate = splitdatestr[1] + '/' + splitdatestr[2] + '/' + splitdatestr[0];
	
	return returndate;
	
}

function getTime(str) {
	
	var splitstr = str.split(" ");
	return splitstr[1];
	
}

function getToday () {
	
	var d = new Date();
	var n = d.getDate();
	var m = d.getMonth()+1;
	var y = d.getFullYear();
	
	var returnstr = m + '/' + n +'/' + y;
	
	return returnstr;
	
}

function getDateCategory (str, tday) {
	
	
	var num = showDays(str, tday);
	
	return num;
	
}

function showDays(firstDate,secondDate){

                  var startDay = new Date(firstDate);
                  var endDay = new Date(secondDate);
                  var millisecondsPerDay = 1000 * 60 * 60 * 24;

                  var millisBetween = startDay.getTime() - endDay.getTime();
                  var days = millisBetween / millisecondsPerDay;

                  // Round down.
                  return( Math.floor(days));

              }
			  
function getDateCategory (numdays, cat) {
	
		var datecat = ''
		var datecatnum = '';
	
	switch (true) {
		
		case (numdays >= -7 && numdays <= 0): 
			
			 datecat = 'Within A Week';
			 datecatnum = 1;
		break;	
		
		case (numdays >= -14 && numdays <= -8): 
			
			 datecat = 'Within Two Weeks';
			 datecatnum = 2;
			
		break;	
		
		case (numdays >= -31 && numdays <= -15): 
			
			 datecat = 'Within A Month';
			 datecatnum = 3;
			
		break;	
		
		case (numdays >= -60 && numdays <= -32): 
			
			 datecat = 'Within Two Months';
			 datecatnum = 4;
			
		break;
		
		case (numdays >= -90 && numdays <= -61): 
			
			 datecat = 'Within Three Months';
			 datecatnum = 5;
			
		break;
		
		case (numdays >= -180 && numdays <= -91): 
			
			 datecat = 'Within Six Months';
			 datecatnum = 6;
			
		break;
		
		case (numdays >= -352 && numdays <= -181): 
			
			 datecat = 'Within A Year';
			 datecatnum = 7;
			
		break;
		
		default:
			 datecat = "All Time"
			 datecatnum = 8;
		
		break;
	}
	
	return (cat == 'cat' ? datecat : datecatnum);
	
}


function formatdata (views, values, standardvalues) {

	//Loop through each instance (viewdata) and get the corresponding values

	

	var instances = _.pluck(views,'instanceID');
	var todaystring = getToday();
	
	_.each(instances, function(instance, index, instances){
		//console.log (instance);
		var valuecount = 0;
		var instancevalues = {};
		var instancevaluesvals = {};
		instancevalues['instanceID'] = instance/1;
		var filteredvalues = _.where(_.flatten(values),{instanceID:instance});
		_.each(filteredvalues, function(filteredval, index, filteredvalues){
			var fieldinfos =  _.where(_.flatten(calcfields),{fieldID: filteredval['field']});
			valuecount = valuecount + 1;
			console.log ('Lookng up: ' + filteredval['field']);
			console.log(fieldinfos);
			if (_.size(fieldinfos) >=1 && fieldinfos[0]['InputType']/1==2) {
			instancevaluesvals[filteredval['field']] = filteredval['value']/1; }
			else {instancevaluesvals[filteredval['field']] = filteredval['value'];}
				
			instancevaluesvals[filteredval['field'] + '-formatted'] = filteredval['formatted_value'];			
		});
		instancevalues['started'] = (valuecount >=1) ? 'Yes' : 'No';
		instancevalues['values'] = instancevaluesvals;
		var filteredvisits = _.where(_.flatten(views),{instanceID:instance});
		//console.log (filteredvisits);
		instancevalues['IP'] = filteredvisits[0]['IP'];
		instancevalues['country'] = filteredvisits[0]['country'];
		instancevalues['stateprov'] = filteredvisits[0]['stateprov'];
		instancevalues['city'] = filteredvisits[0]['city'];
		instancevalues['lat'] = filteredvisits[0]['lat'];
		instancevalues['long'] = filteredvisits[0]['long'];
		instancevalues['datetimeViewed'] = filteredvisits[0]['dateCreated'];
		instancevalues['dateViewed'] = getDate(filteredvisits[0]['dateCreated']);
		instancevalues['timeViewed'] = getTime(filteredvisits[0]['dateCreated']);
		instancevalues['Today'] = todaystring;
		instancevalues['dayslapsed'] = showDays(instancevalues['dateViewed'],todaystring);
		instancevalues['lapsecategory'] = getDateCategory(instancevalues['dayslapsed'],'cat');
		instancevalues['lapsecategorynum'] = getDateCategory(instancevalues['dayslapsed'],'num');
		alldata.push(instancevalues);
	});
	var allstarted = _.where(_.flatten(alldata),{started:'Yes'});
	//console.log (JSON.stringify(allstarted));
	
	
	getallinstancetabledata(timeperiodselected);
	getaverages();
}

function getallinstancetabledata(timeperiod) {
	gettablecols(roiid);
}


function getallinstancetabledata_cont(timeperiod) {
	
	
	console.log ('tablecols: ' + JSON.stringify(tablecols));

	var allstarted = _.where(_.flatten(alldata),{started:'Yes'});
	var filtered = _.filter(allstarted, function(item) {
		 return item.lapsecategorynum <= timeperiod
	});
	
	filtered = _.sortBy(filtered, 'dateViewed');
	
	var tableinfo='';
	var tableheader = '<tr><th></th>';
	
	tablecols = _.sortBy(tablecols, 'columnOrder');
	console.log ('tablecols: ' + JSON.stringify(tablecols));
	_.each (tablecols, function(tcol, index, tablecols){
		tableheader = tableheader + '<th>' + tcol['columnHeader'] + '</th>';
		
	});
	tableheader = tableheader + '</tr>';
	
	
	/*
	<th></th>
	<th>InstanceID</th>
	<th>Date Viewed</th>
	<th>Time Viewed</th>
	<th>IP Address</th>
	<th>Value</th>
	//*/
	
	_.each(filtered, function(instanceval, index, filtered){
		tableinfo = tableinfo + '<tr><td><button type="button" class="btn btn-primary moreinstancedetails" data-instanceid="' + instanceval['instanceID'] + '" data-toggle="modal" data-target="#modalmoredetails">...</button></td><td>' + instanceval['instanceID'] + '</td><td>' + instanceval['dateViewed'] + '</td><td>' + instanceval['timeViewed'] + '</td><td>' + instanceval['IP'] + '</td><td>' + instanceval['values']['1068-formatted'] + '</td><td>' + instanceval['values']['1069-formatted'] + '</td>' + 
		'<td>' + instanceval['values']['1070-formatted'] + '</td><td>' + instanceval['values']['1071-formatted'] + '</td><td>' + instanceval['values']['1129-formatted'] + '</td>' + 
		'<td>' + instanceval['values']['1130-formatted'] + '</td><td>' + instanceval['values']['1131-formatted'] + '</td></tr>';
		
	});
	
	tableinfo=tableheader;
	
	_.each(filtered, function(instanceval, index, filtered){
		tableinfo = tableinfo + '<tr>';
		tableinfo = tableinfo + '<td><button type="button" class="btn btn-primary moreinstancedetails" data-instanceid="' + instanceval['instanceID'] + '" data-toggle="modal" data-target="#modalmoredetails">...</button></td>';
			_.each (tablecols, function(tcol, index, tablecols){
				var colname = tcol['fieldID'] + '-formatted';
				tableinfo = tableinfo + '<td>' +  instanceval['values'][colname] + '</td>';
				
			});
		tableinfo = tableinfo + '</tr>';
	});
	
	console.log ('all values: ' + JSON.stringify(filtered));

	$('#allinstancedatatable').html(tableinfo);
	
	/* format the table */
	
	$('.dataTables-example').dataTable({
		responsive: true,
		"dom": 'T<"clear">lfrtip'
	});


		
		/* Init DataTables */
	var oTable = $('#editable').dataTable();

	/* Apply the jEditable handlers to the table */
	oTable.$('td').editable( '../example_ajax.php', {
		"callback": function( sValue, y ) {
			var aPos = oTable.fnGetPosition( this );
			oTable.fnUpdate( sValue, aPos[0], aPos[1] );
		},
		"submitdata": function ( value, settings ) {
			return {
				"row_id": this.parentNode.getAttribute('id'),
				"column": oTable.fnGetPosition( this )[2]
			};
		},

		"width": "90%",
		"height": "100%"
	} );
		
		
	

	
	
		
}



		var viewdata = [];
		var valuedata = [];
		var valuestddata = [];
		var alldata = [];
		var calcfields = [];
		var averages = [];
		var tablecols = [];
		var timeperiodselected = 3;
		
		
		var roiid = $('#roiID').val();
		
        $(document).ready(function() {
			
			getviews(roiid);
			
			
			//getvalues(roiid);
			
			//formatdata (viewdata,valuedata, valuedata);
			
			
			
			
            


        });


		$('#allinstancedatatable').on('click', '.moreinstancedetails', function() {
		var instanceid = $(this).data('instanceid');
		    $('#mdinstanceID').html(instanceid);
		console.log (alldata)
		var selectedinstancevalues = _.where(_.flatten(alldata),{instanceID: instanceid});
		console.log (selectedinstancevalues);
		$('.getinstancevalue').each(function(){
			console.log ('looping');
			var fieldid = $(this).data('fieldid');
			$(this).html(selectedinstancevalues[0]['values'][fieldid + '-formatted']);
			
		})
	
	});

	
		
        
        // Change the Time Interval to the specified time clicked.
        
		$('.editablecols').click(function(){
			
			
			
		});
		
		
		$('.intervalchange').click(function(){
			
			$('.intervalchange').each(function(){
				$(this).removeClass('btn-primary');
				$(this).addClass('btn-white');
			});
			
			$(this).removeClass('btn-white');
			$(this).addClass('btn-primary');
			
			var timeint = $(this).data('interval');
			timeperiodselected = timeint;
			
			getallinstancetabledata(timeint);
			
		});
		
		
         $(".timechange").click(function(){
		    var interval = $(this).data('interval');
		    window.location = 'index.php?wbappID=<?php echo $roiID; ?>&key=<?php echo $key; ?>&r=' + interval;
		    //
		    
		    
		    
		});
		
		
		$(".selectfield").click(function(){
		    var fieldid = $(this).data('fieldid');
		    //console.log (fieldid);
		    var starting = '2018-02-28';
		    var ending = '2018-03-28';
		    $.ajax({
			type	: 	"POST",
			url		:	"ajax_getfieldusage.php",
			data	:	'fieldid=' + fieldid + '&starting=' + starting + '&ending=' + ending,
			success	:	function(returnarray) {
				console.log(returnarray);
				console.log('ajax returned successful');
				
				//var totalarray = JSON.parse(returnarray);
				//var bucketarray = totalarray['bucket'];
				//var valuearray = totalarray['values'];
				
				//var bucketstring = bucketarray.join(",");
				//var valuestring = valuearray.join(","); 
				
				var parsed = JSON.parse(returnarray);
				
				console.log ('values: ' + parsed);
				
				
				
				var fieldchart = $('#field_details').highcharts();
				//fieldchart.xAxis[0].setCategories(bucketarray);
				fieldchart.series[0].setData(parsed);
				
				 //$('#field_details').highcharts().redraw();
			}
			});//end ajax
		    //
		    
		    
		    
		});
		
    	
        
    </script>



