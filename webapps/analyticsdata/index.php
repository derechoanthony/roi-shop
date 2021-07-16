
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

.rightalign {
	text-align: right;
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
			<button class="btn btn-white intervalchange" type="button"  data-interval="1" data-timeframetext="Viewing Data for the last 7 days">Week</button>
			<button class="btn btn-white intervalchange" type="button"  data-interval="2" data-timeframetext="Viewing Data for the last 4 days">2 Weeks</button>
			<button class="btn btn-primary intervalchange" type="button" data-interval="3" data-timeframetext="Viewing Data for the last 30 days">Month</button>
			<button class="btn btn-white intervalchange" type="button" data-interval="4" data-timeframetext="Viewing Data for the last 60 days">2 Months</button>
			<button class="btn btn-white intervalchange" type="button" data-interval="5" data-timeframetext="Viewing Data for the last 90 days">3 Months</button>
			<button class="btn btn-white intervalchange" type="button" data-interval="6" data-timeframetext="Viewing Data for the last 180 days">6 Months</button>
			<button class="btn btn-white intervalchange" type="button" data-interval="7" data-timeframetext="Viewing Data for the last year">Year</button>
			<button class="btn btn-white intervalchange" type="button" data-interval="8" data-timeframetext="Viewing Data since calculator start">All Time</button>
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
            <h5>Instance Data <small id="timeframetext">Viewing Data for the last 30 days</small></h5>
            
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-sm-9 m-b-xs">
				
                    <div data-toggle="buttons" class="btn-group" style="display: none;">
                        <label class="btn btn-sm btn-white"> <input type="radio" id="option1" name="options"> All  </label>
                        <label class="btn btn-sm btn-white active"> <input type="radio" id="option2" name="options"> Production </label>
                        <label class="btn btn-sm btn-white"> <input type="radio" id="option3" name="options"> Testing </label>
                    </div>
                </div>
                
            </div>
			<div id="instanceinfotable1">
            <table id="maintable" class="table table-striped table-bordered table-hover dataTables-render" >
			<div id="instanceinfotable">
			<thead>
			
				
				<div id="headerlist">
				
				</div>
			
			</thead>
			<tbody id="allinstancedatatable"></tbody><tfooter>
			
			</tfoot>
			</div>
			</table>
			</div>
			
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
                                                <th class="rightalign">User Generated Value</th>
                                                
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
                                        <table class="table table-hover ">
                                            <thead>
                                            <tr>
                                                <th>Calculated Field</th>
                                                <th class="rightalign">User Generated Value</th>
                                                
                                            </tr>
                                            </thead>
                                            <tbody id="calcfieldstable">
                                            
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
                                                <th class="rightalign">User Generated Value</th>
                                            </tr>
                                            </thead>
                                            <tbody id="contactfieldstable">
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                </div>
								
								<div id="tab-4" class="tab-pane">
                                    <div class="ibox float-e-margins">
                                    
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins">
                                            <thead>
                                            <tr>
                                                <th>Automated Field</th>
                                                <th class="rightalign">Captured Value</th>
                                                
                                            </tr>
                                            </thead>
                                            <tbody id="stdfieldstable">
											<tr><td>Date Viewed</td><td class="rightalign"><span class="getinstancestdvalue" data-fieldid="5" id="fieldvalue_972" <="" span=""></span></td></tr>
											<tr><td>IP Address</td><td class="rightalign"><span class="getinstancestdvalue" data-fieldid="1" id="fieldvalue_973" <="" span=""></span></td></tr>
											<tr><td>Location (Country)</td><td class="rightalign"><span class="getinstancestdvalue" data-fieldid="2" id="fieldvalue_974" <="" span=""></span></td></tr>
											<tr><td>Location (State)</td><td class="rightalign"><span class="getinstancestdvalue" data-fieldid="3" id="fieldvalue_975" <="" span=""></span></td></tr>
											<tr><td>Location (City)</td><td class="rightalign"><span class="getinstancestdvalue" data-fieldid="4" id="fieldvalue_976" <="" span=""></span></td></tr>
											<tr><td>PDF Report Link</td><td class="rightalign"><span class="getinstancestdvalue" data-fieldid="18" id="fieldvalue_977" <="" span=""></span></td></tr>
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
						<button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
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

function getallinfo(roiid) {
	
	console.log (roiid);
	
	var start = new Date().getTime();
	
	//Get the view data from ajax call
	$.ajax({
	  type: 'POST',
	  url: 'ajax_getallinfo.php',
	  data: 'roiid=' + roiid,
	  cache: false,
	  success: function(data) {
		//console.log (data);
		alldata = JSON.parse(data);
		console.log (alldata);
		//console.log (data);
		 console.log ('Getting All Info: ');
		 console.log(new Date().getTime() - start);
		//getvalues(roiid)
		
		processdata();
		loadTable();
		loadmodaltables();
	  },
	  });
	
}


function processdata() {
	
	d = alldata;
	
	instances = d['instances'];
	var instancevalues = d['instancevalues'];
	var instancestdvalues = d['instancestdvalues'];
	//console.log (instancevalues);
	newinstances = [];
	
	_.each(instances, function(i) {
		var ni = {};
		var id = i.instanceID;
		ni['instanceID'] = i.instanceID;
		ni['IP'] = i.IP
		ni['Country'] = i.country;
		ni['State'] = i.stateprov;
		ni['DateTime'] = i.dateCreated;
		ni['ShortDate'] = shortDate(i.dateCreated);
		ni['DateInterval'] = dateInterval(i.dateCreated);
		ni['CalcValues'] = _.first(_.values(_.pick(instancevalues,i.instanceID)));
		ni['StdValues'] = _.first(_.values(_.pick(instancestdvalues,i.instanceID)));
		newinstances.push(ni);
	});
	
	instancedata = newinstances;
	console.log (instancedata);
}

function loadTable () {
	
	//Time period selected
	tp = timeperiodselected;
	
	//Instance Data
	instancedata = instancedata;
	
	//Reject all instances where DateInterval Is Greater than tp
	var intervals = [1,2,3,4,5,6,7,8];
	intervals = _.reject(intervals, function(interval){
		return interval<=tp;
	} );
	
	var id = instancedata;
	_.each(intervals, function(interval) {
		id = _.reject(id, {DateInterval: interval});
	});
		
	id = _.reject(id, function(i) {
		return _.isUndefined(i.CalcValues);
	});
	
	id = _.sortBy(id, 'instanceID');
	
	console.log ('instances');
	console.log (id);
	
	var tablestr = '<table class="table table-striped table-bordered table-hover dataTables-example" >';
	//var tablestr = '';
    tablestr = tablestr + '<thead>';
    tablestr = tablestr + '<tr><th>Details</th>';
	tablestr = tablestr + '<th>View Date</th>';
	
    
	var tablecols = alldata['tablecolumns'];
	tablecols = _.sortBy(tablecols, 'columnOrder');
	var cols = _.pluck(tablecols, 'fieldID');
	_.each (tablecols, function(col){
		tablestr = tablestr + '<th>' + col.columnHeader + '</th>';
	});
	
	tablestr = tablestr + '</tr></thead><tbody>';
	
	_.each (id, function(i){
		tablestr = tablestr + '<tr>';
		tablestr = tablestr + '<td><button type="button" class="btn btn-small btn-primary moreinstancedetails" data-instanceid="' + i.instanceID + '" data-toggle="modal" data-target="#modalmoredetails">...</button></td>';
		tablestr = tablestr + '<td>' + i.ShortDate + '</td>';
		_.each(cols, function(c){
			//If column is calc column get value from i.CalcValues Array
			var cvals = i.CalcValues;
			var cval = _.where(cvals, {field: c });
			
			if(_.size(cval)==0 || typeof cval[0]['value'] == "") {
				//field was not populated by user
				tablestr = tablestr + '<td> --- </td>';
			}
			else {
				//value was populated by user
				tablestr = tablestr + '<td>' + blankundefined(cval[0]['value']) + '</td>';
			}
			
			
			
			//If column is standard column get value from i.StdValues Array

		
		});
		tablestr = tablestr + '</tr>';
		
		
	});
	
	tablestr = tablestr + '</tbody>';
	
	$('#instanceinfotable').html(tablestr);
	
	$('.dataTables-example').DataTable({
	responsive: false,
				"columnDefs": [{ "visible": true, "targets": 0 }],
				"order":[[1,"desc"]],
                "dom": '<"toolbar">frtip',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
	}}
	
	);
}

function loadmodaltables() {
	
	var c = alldata;
	c = alldata['calcfields'];
	c = _.sortBy(c, 'cellcolumn');
	c = _.sortBy(c, 'cell');
	c = _.sortBy(c, 'fieldType');
	
	
	console.log ('Modal Table:');
	console.log (c);
	
	var inputtablehtml = '';
	
	cinput = _.where(c, {fieldType: '1'});
	_.each(cinput, function(ci){
		inputtablehtml = inputtablehtml + '<tr><td>' + ci['shortName'] + '</td><td class="rightalign"><span class="getinstancevalue" data-fieldid="' + ci['fieldID'] + '" id="fieldvalue_' + ci['fieldID'] + '"</span></td></tr>';
	});
		
	$('#inputfieldstable').html(inputtablehtml);
	
	var inputtablehtml = '';
	
	cinput = _.where(c, {fieldType: '2'});
	_.each(cinput, function(ci){
		inputtablehtml = inputtablehtml + '<tr><td>' + ci['shortName'] + '</td><td class="rightalign"><span class="getinstancevalue" data-fieldid="' + ci['fieldID'] + '" id="fieldvalue_' + ci['fieldID'] + '"</span></td></tr>';
	});
	
	$('#calcfieldstable').html(inputtablehtml);
	
	var inputtablehtml = '';
	
	cinput = _.where(c, {fieldType: '3'});
	_.each(cinput, function(ci){
		inputtablehtml = inputtablehtml + '<tr><td>' + ci['shortName'] + '</td><td class="rightalign"><span class="getinstancevalue" data-fieldid="' + ci['fieldID'] + '" id="fieldvalue_' + ci['fieldID'] + '"</span></td></tr>';
	});
	
	$('#contactfieldstable').html(inputtablehtml);
	
}

//Misc Functions

function blankundefined(str) {
	
	if(typeof str === "undefined") {var returnstr = '---';} else {var returnstr = str;}
	
	return returnstr;
	
}


//Start Date Functions

function shortDate(datestring){

	if (typeof datestring === "undefined") {
		var returnstring = '';
	}
	else 
	{
	var rd = datestring.split(" ");
	var rdd = rd[0];

	var daterdd = rdd.split("-");
	var yearstr = daterdd[0];
	var monthstr = daterdd[1];
	var daystr = daterdd[2];

	var returnstring = monthstr + '/' + daystr + '/' + yearstr;

	}

	return returnstring;

}

function dateDif(startdate, enddate) {
	//Pass 'Today' as one variable to get difference from today
	if(typeof startdate ==="undefined" ||typeof enddate==="undefined" ) {
		var returnval = 'Ongoing';
	}
	else {

	date1str = (startdate=='Today' ? '' : parseDateInfo(startdate));
	date2str = (enddate=='Today' ? '' : parseDateInfo(enddate));


	

	date1 = (startdate != 'Today' ? new Date(date1str) : new Date ());
	date2 = (enddate != 'Today' ? new Date(date2str) : new Date ());
	date2 = new Date();

	var returnval =  Math.round((date2-date1)/(1000*60*60*24));
	
	}
	return returnval;

}

function parseDateInfo (datestring) {
	if (typeof datestring === "undefined" || datestring == 'Today' ) {
		var newstr='';
	} 
	else 
	{
		
	var rd = datestring.split(" ");
	var rdd = rd[0];

	var daterdd = rdd.split("-");
	var yearstr = daterdd[0];
	var monthstr = daterdd[1];
	var daystr = daterdd[2];

	var newstr = monthstr + '/' + daystr + '/' + yearstr;

	}
	return newstr;
};

function dateInterval(datestring) {

	var datedif = dateDif(datestring, 'Today');
	
	var interval=0;
	if(datedif>360) {interval = 8;}
	if(datedif<=360) {interval = 7;}
	if(datedif<=180) {interval = 6;}
	if(datedif<=90) {interval = 5;}
	if(datedif<=60) {interval = 4;}
	if(datedif<=30) {interval = 3;}
	if(datedif<=14) {interval = 2;}
	if(datedif<=7) {interval = 1;}
	
	return interval;
	
}

//End Date Functions

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


		var alldata = [];
		var instancedata = [];

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
			
			getallinfo(roiid);
			//getviews(roiid);
			//getvalues(roiid);
			//getstdvalues(roiid);
			//gettablecols(roiid);
			//formatdata (viewdata,valuedata, valuedata);
			
			
			
			
            


        });


		$('#instanceinfotable').on('click', '.moreinstancedetails', function() {
		var instanceid = $(this).data('instanceid');
		    $('#mdinstanceID').html(instanceid);
		console.log ('Working with:');
		console.log (instancedata)
		console.log ('filtering for:');
		console.log (instanceid);
		var id = instanceid.toString();
		var i = _.where(instancedata, {instanceID: id});
		console.log ('Single Instance:')
		console.log (i);
		$('.getinstancevalue').each(function(){
			//console.log ('looping');
			var fieldid = $(this).data('fieldid');
			//console.log (i);
			//console.log (fieldid);
			var f = fieldid.toString();
			var calcvalues = i[0]['CalcValues'];
			//console.log (calcvalues);
			var cvalue = _.first(_.where(calcvalues, {field: f}));
			if (_.size(cvalue)==0) {var val = 'Not Given'} else {var val = cvalue['formatted_value'];}
			//console.log (cvalue);
			//console.log (val);
			$(this).html(val);
			
		});
		$('.getinstancestdvalue').each(function(){
			console.log ('looping');
			var fieldid = $(this).data('fieldid');
			console.log (i);
			console.log (fieldid);
			var f = fieldid.toString();
			var stdvalues = i[0]['StdValues'];
			//console.log (calcvalues);
			var cvalue = _.first(_.where(stdvalues, {stdfieldID: f}));
			if (_.size(cvalue)==0) {var val = '---'} else {var val = cvalue['value'];}
			console.log (cvalue);
			console.log (val);
			$(this).html(val);
			
		});
	
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
			
			var timetext = $(this).data('timeframetext');
			$('#timeframetext').html(timetext);
			
			loadTable();
			
			
		});
		
		

		
		
	
		
    	
        
    </script>



