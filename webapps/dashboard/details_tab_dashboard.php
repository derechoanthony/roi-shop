
                                	
					                
                                	
<div class="row  border-bottom white-bg dashboard-header">

					<div class="row ">
						<div class="col-sm-12">
							<span class="pull-right">
								<h3>Demo Status</h3>
							</span>
							<h1><?php echo '<strong>' . $g->DLookup('roiName','wb_roi_list','wb_roi_ID=' . $roiID) . '</strong>';?></h1>
							<hr>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<h4>Show Results For Past:</h4>
							<div class="btn-group">
                                <button class="btn <?php echo ($r ==1 ? 'btn-primary' : 'btn-white')?> timechange" type="button"  data-interval="1">Week</button>
                                <button class="btn <?php echo ($r ==2 ? 'btn-primary' : 'btn-white')?> timechange" type="button"  data-interval="2">2 Weeks</button>
                                <button class="btn <?php echo ($r ==3 ? 'btn-primary' : 'btn-white')?> timechange" type="button" data-interval="3">Month</button>
                                <button class="btn <?php echo ($r ==4 ? 'btn-primary' : 'btn-white')?> timechange" type="button" data-interval="4">2 Months</button>
                                <button class="btn <?php echo ($r ==5 ? 'btn-primary' : 'btn-white')?> timechange" type="button" data-interval="5">3 Months</button>
                                <button class="btn <?php echo ($r ==6 ? 'btn-primary' : 'btn-white')?> timechange" type="button" data-interval="6">6 Months</button>
                                <button class="btn <?php echo ($r ==7 ? 'btn-primary' : 'btn-white')?> timechange" type="button" data-interval="7">Year</button>
                                <button class="btn <?php echo ($r ==8 ? 'btn-primary' : 'btn-white')?> timechange" type="button" data-interval="8">All Time</button>
                            </div>
                            <hr>
						</div>
						
					</div>
					
					<div class="row">

                    
                    
                    <div class="col-sm-9">
                        <h4>
                            Calculator Views / Calculator Starts
                            
                        
                        
                            </h4>
                            <div style="width: 100%; height: 350px; margin: 0" class="contains-chart"  id="timechart1"></div>
                            
                            
                            <?php 
                        
                        
                        /*$SQL = "SELECT CONCAT('Date.UTC(',date_format(t1.dateCreated,'%Y,%m,%d'),')') as created_date,
								     date_format(t1.dateCreated,'%Y') as ViewYear,
								     date_format(t1.dateCreated,'%m') as ViewMonth,
								     date_format(t1.dateCreated,'%d') as ViewDay,
								     COUNT(DISTINCT t1.instanceID) AS Views,
								     GROUP_CONCAT(t2.stdfieldID SEPARATOR '/?/') AS stdfieldIDkey, 
								     GROUP_CONCAT(t2.value SEPARATOR '/?/') AS stdfieldvalues
								FROM wb_roi_instance t1
								JOIN wb_roi_instance_values_standard t2
								ON t1.instanceID=t2.instanceID
								WHERE YEAR(t1.dateCreated) = '2018' 
								     AND MONTH(t1.dateCreated) = '03' 
								     AND wbroiID=$wbappID
								GROUP BY created_date";
                        
						 * 
						 */
                        
                        $currentdate = date("Y-m-d");
						//$currentdate = date_create($currentdate);
						
                        $currentmonth = date("m");
						$currentyear = date("Y");
						
						//$currentdate = '2007-01-04 12:34:31' ; 

//$oneWeekAgo = strtotime ( '-1 week' , $currentdate ) ; 
//$oneWeekAgo = date("Y-m-d");
//date_sub($oneWeekAgo,date_interval_create_from_date_string("7 days"));
//$oneMonthAgo = strtotime ( '-1 month' , strtotime ( $currentdate ) ) ; 

//echo date ( 'Y-m-j G:i:s' , $oneWeekAgo ) . "<br />\n" ; 
						
						
//$date = date_create();
//date_sub($date, date_interval_create_from_date_string('10 days'));
//echo '<br><br><br> Trying date: ' . date_format($date, 'Y-m-d');

//$date = new DateTime("2017-05-18"); // For today/now, don't pass an arg.
//$date->modify("-1 day");
//echo $date->format("Y-m-d H:i:s");

    $oneWeekAgo = new DateTime('2018-03-28', new DateTimeZone('America/New_York'));
	$oneWeekAgo->modify("-7 days");
    
    
	


						
                        switch ($r) {
                        	
							case 1:
								
								$startdate = new DateTime('', new DateTimeZone('America/New_York'));
								$startdate->modify("-7 days");
								$startdatestng = $startdate->format('Y-m-d');
								$wheresql = "WHERE dt BETWEEN '" . $startdate->format('Y-m-d') . "' AND '$currentdate'";
								
							break;
								
							case 2:
								$startdate = new DateTime('', new DateTimeZone('America/New_York'));
								$startdate->modify("-14 days"); 
								$startdatestng = $startdate->format('Y-m-d');
								$wheresql = "WHERE dt BETWEEN '" . $startdate->format('Y-m-d') . "' AND '$currentdate'";
								
							break;
								
							case 3:
								$startdate = new DateTime('', new DateTimeZone('America/New_York'));
								$startdate->modify("-1 month"); 
								$startdatestng = $startdate->format('Y-m-d');
								$wheresql = "WHERE dt BETWEEN '" . $startdate->format('Y-m-d') . "' AND '$currentdate'";
								
							break;
							
							case 4:
								$startdate = new DateTime('', new DateTimeZone('America/New_York'));
								$startdate->modify("-2 months"); 
								$startdatestng = $startdate->format('Y-m-d');
								$wheresql = "WHERE dt BETWEEN '" . $startdate->format('Y-m-d') . "' AND '$currentdate'";
								
							break;
							
							case 5:
								$startdate = new DateTime('', new DateTimeZone('America/New_York'));
								$startdate->modify("-3 months"); 
								$startdatestng = $startdate->format('Y-m-d');
								$wheresql = "WHERE dt BETWEEN '" . $startdate->format('Y-m-d') . "' AND '$currentdate'";
								
							break;
							
							case 6:
								$startdate = new DateTime('', new DateTimeZone('America/New_York'));
								$startdate->modify("-6 months"); 
								$startdatestng = $startdate->format('Y-m-d');
								$wheresql = "WHERE dt BETWEEN '" . $startdate->format('Y-m-d') . "' AND '$currentdate'";
								
							break;
							
							case 7:
								$startdate = new DateTime('', new DateTimeZone('America/New_York'));
								$startdate->modify("-1 year"); 
								$startdatestng = $startdate->format('Y-m-d');
								$wheresql = "WHERE dt BETWEEN '" . $startdate->format('Y-m-d') . "' AND '$currentdate'";
								
							break;
							
							case 3:
								$startdate = new DateTime('2016-01-01', new DateTimeZone('America/New_York'));
								$startdatestng = $startdate->format('Y-m-d');								
								$wheresql = "WHERE dt BETWEEN '" . $startdate->format('Y-m-d') . "' AND '$currentdate'";
								
							break;
							
							default:
								$wheresql = "WHERE y=$currentyear AND m=$currentmonth";
							
                        }
                        
                        
                        $SQL = "SELECT CONCAT('Date.UTC(',date_format(t1.dt,'%Y,%m,%d'),')') as created_date,
									date_format(t1.dt,'%Y') as ViewYear,
									date_format(t1.dt,'%m') as ViewMonth,
									date_format(t1.dt,'%d') as ViewDay,
									(SELECT COUNT(instanceID) FROM wb_roi_instance t2 WHERE DATE_FORMAT(t2.dateCreated, '%Y-%m-%d')=t1.dt AND t2.wbroiID=$wbappID) AS Views,
									(SELECT COUNT(DISTINCT instanceID) FROM wb_roi_instance_values t2 WHERE DATE_FORMAT(t2.dateCreated, '%Y-%m-%d')=t1.dt AND t2.wb_roi_ID=$wbappID) AS Starts
									FROM `wb_ref_calendar` t1
									$wheresql";
                        
                        //echo $SQL;
                        
                        $list = $g->returnarray($SQL);
                      
                      
                      // To add concatenated values
                      /*
									(SELECT GROUP_CONCAT(stdfieldID SEPARATOR '/?/') FROM wb_roi_instance_values_standard t2 WHERE DATE_FORMAT(t2.dateCreated, '%Y-%m-%d')=t1.dt AND t2.wb_roi_ID=$wbappID) AS stdfieldIDkey,
									(SELECT GROUP_CONCAT(value SEPARATOR '/?/') FROM wb_roi_instance_values_standard t2 WHERE DATE_FORMAT(t2.dateCreated, '%Y-%m-%d')=t1.dt AND t2.wb_roi_ID=$wbappID) AS stdfieldvalues
						
					   * 
					   */
						
						$numrows = count($list);
				        
						$viewschart= array();
						$stdvalarray = array();
						$x=0;
						
						$totalcount = 0;
						$startcount = 0;
						
						if($numrows>0){
							foreach($list as $r){
								
								$vals 		= array();
								//$totalviews = array();
								
								$y = $r['ViewYear'];
								$m = $r['ViewMonth'] / 1 - 1;	//Need to decrease month by one for use in Javascript.  January = 0
								$d = $r['ViewDay'];
								
								$vals[0]=$r['created_date'];
								
								$vals[1]=$r['Views'];
								
								$totalcount = $totalcount + $r['Views'];
								$startcount = $startcount + $r['Starts'];
								
								$totalviews = "[Date.UTC($y, $m, $d), " . $r['Views'] . "]";
								$startviews1 = "[Date.UTC($y, $m, $d), " . $r['Starts'] . "]";
								
								
								
								$viewschart[$x]=$totalviews;
								$viewschart1[$x]=$startviews1;
								
								$x=$x + 1;
								
								}
						}
						
						
						$onlyviewed = $totalcount-$startcount;
						
						//echo '<br><br><br>Total Count: ' . $totalcount;
						//echo '<br><br><br>Start Count: ' . $startcount;
						
						$jso = implode(',', $viewschart);
						$jso1 = implode(',', $viewschart1);
						$jso2 = implode(',', $viewschart1_1);
						
						//echo $SQL;
						
						//print_r($jso1);
						
						//print_r($viewschart);
						
						//$viewschart1 = json_encode($viewschart, JSON_NUMERIC_CHECK);
                        
                        //echo json_encode($viewschart, JSON_NUMERIC_CHECK);
						
						?>
                            
                        <script>
                                		$(function () { 
                                			console.log ([<?php echo $jso;?>]);
                                			
										    $('#timechart1').highcharts({
										        chart: {
											        type: 'areaspline'
											    },
											    title: {
											        text: ''
											    },
											    credits: {enabled: false},
											    xAxis: {
											        type: 'datetime',
											        dateTimeLabelFormats: { // don't display the dummy year
											            month: '%e. %b',
											            year: '%b'
											        },
											        title: {
											            text: ''
											        }
											    },
											    yAxis: {
											        title: {
											            text: ''
											        },
											        min: 0
											    },
											    tooltip: {
											        headerFormat: '<b>{series.name}</b><br>',
											        pointFormat: '{point.y} Instances '
											    },
											
											    plotOptions: {
											        spline: {
											            marker: {
											                enabled: true
											            }
											        }
											    },
											
											    series: [{
											        name: 'Total Views',
											        // Define the data points. All series have a dummy year
											        // of 1970/71 in order to be compared on the same x axis. Note
											        // that in JavaScript, months start at 0 for January, 1 for February etc.
											        data: [<?php echo $jso;?>],
											        color: '#6E868D'
											    },{
											        name: 'Calculator Starts',
											        // Define the data points. All series have a dummy year
											        // of 1970/71 in order to be compared on the same x axis. Note
											        // that in JavaScript, months start at 0 for January, 1 for February etc.
											        data: [<?php echo $jso1;?>],
											        color: '#0058A2'
											    }]
										    });
										});
                                		
                                		
                                	</script>
                        
                    </div>
                    
                    
                    
                    <div class="col-md-3">
                          
                            <div style="width: 75%; height: 350px; margin: 0" class="contains-chart"  id="calcstarts"></div>          
                                    
                         <script>
                                		$(function() {
									        // Create the chart
									        chart = new Highcharts.Chart({
									            chart: {
									                renderTo: 'calcstarts',
									                type: 'pie'
									               
									            },
									            title: {text: null},
									            credits: {enabled: false},
									            legend: {enabled: false},
									            
									            plotOptions: {
									                pie: {
									                    shadow: false,
									                    startAngle: 180
									                }
									            },
									            tooltip: {
									                formatter: function() {
									                    return '<b>'+ this.point.name +'</b>: '+ this.y;
									                }
									            },
									            series: [{
									                name: 'Views',
									                data: [["Starts",<?php echo $startcount; ?>],["NonStarts",<?php echo $totalcount - $startcount; ?>]],
									                size: '80%',
									                colors: ['#0058A2','#BBC9CD'],
									                innerSize: '60%',
									                showInLegend:true,
									                dataLabels: {
									                    enabled: false
									                }
									            }]
									        });
									    });
                                		
                                	</script>           
                                    
                    </div>
                    
                    
                    
				</div>
					
					
					
					<div class="row">
						
						
						
						
						
					</div>
	
					
            </div>
                                	
                                	
                                	
                                	
                                	
                                	<hr>
                                	
                                	<!--  Test -->
                                		
                                <div class="row">
                                	
                                	<div class="col-sm-12">
                                		 <div class="panel panel-default">
                                        <div class="panel-heading">
                                             
					                        <h3>Field Analysis</h3>
                                        </div>
                                        <div class="panel-body">
                                             <div class="col-sm-4">
                                             	<?php //echo $listitems; ?>
                                             	<table class="table table-hover no-margins">
                                            <thead>
                                            <tr>
                                                <th>Field No.</th>
                                                <th>Label</th>
                                                
                                                <th>Completion Rate</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            	
                                      <?php 
                        
			                        $SQL = "SELECT *, 
			                        			(SELECT COUNT(instanceID) FROM wb_roi_instance t2 WHERE t2.wbroiID=t1.wb_roi_ID AND BETWEEN '" . $startdatestng . "' AND '" . $currentdate . "') TotalViews,
			                        			(SELECT COUNT(valueID) FROM wb_roi_instance_values t3 WHERE t3.field=t1.fieldID AND EXTRACT(YEAR FROM t3.modified)>0) FieldChanges,
			                        			(((SELECT COUNT(valueID) FROM wb_roi_instance_values t3 WHERE t3.field=t1.fieldID AND EXTRACT(YEAR FROM t3.modified)>0) / (SELECT COUNT(instanceID) FROM wb_roi_instance t2 WHERE t2.wbroiID=t1.wb_roi_ID AND status=1))*100) AS PercentChanged
			                        		FROM wb_roi_fields t1
			                        		WHERE wb_roi_ID=$wbappID
			                        				AND fieldType=1
											ORDER BY cell ASC;";
											
									$SQL = "SELECT *, 
											(SELECT COUNT(instanceID) FROM wb_roi_instance t2 WHERE t2.wbroiID=t1.wb_roi_ID AND t2.dateCreated BETWEEN '" . $startdatestng . "' AND '" . $currentdate . "') TotalViews, 
											(SELECT COUNT(valueID) FROM wb_roi_instance_values t2 WHERE t2.wb_roi_ID=t1.wb_roi_ID AND t1.fieldID=t2.field AND t2.value>0 AND t2.dateCreated BETWEEN '" . $startdatestng . "' AND '" . $currentdate . "') UsageCount 
											FROM wb_roi_fields t1 WHERE wb_roi_ID=10017 AND fieldType=1 
											ORDER BY cell ASC";
									//echo $SQL;
											
									$list = $g->returnarray($SQL);
			                        $listitems = '';
									
									$numrows = count($list);
							        $x = 0;
									
									if($numrows>0){
										foreach($list as $r){
											$x = $x + 1;
											$listitems = $listitems .'<tr> ';
											$listitems = $listitems .'<td><span class="label label-primary">' . ($x/1) . '</span></td> ';
											$listitems = $listitems .'<td>' . $r['shortName'] . '</td> ';
											
				                        	$listitems = $listitems .'<td class="text-navy">' . number_format(($r['UsageCount'] / $r['TotalViews']) * 100,0) . '% </td>';
				                        	$listitems = $listitems . '<td><button type="button" class="btn btn-w-m btn-success btn-xs selectfield" data-fieldid="' . $r['fieldID'] . '">More Details ' . $r['fieldID'] . '</button></td>';
				                        	$listitems = $listitems . '</tr>';
											}
									}
										
			
			                        ?>
                                            	
                                            	
                                            <?php echo $listitems; ?>
                                            </tbody>
                                        </table>
                                             </div>
					                        
					                     
					                     <div class="col-sm-8">
					                     	
					                     	<h4>Response Frequency</h4>
					                     	
					                     	<div style="width: 100%; height: 450px; margin: 0" class="contains-chart"  id="field_details"></div>
					                     	
					                     	
					                     	<?php 
					                     	
					                     	$SQL = "SELECT ROUND(value, -2)    	   AS bucket,
												       COUNT(*)                    AS COUNT,
												       RPAD('', LN(COUNT(*)), '*') AS bar
												FROM   wb_roi_instance_values
												WHERE field=185 
													AND dateCreated BETWEEN '" . $startdatestng . "' AND '" . $currentdate . "'
												GROUP  BY bucket;";
					                     	
											$results = $g->returnarray($SQL);
											
											$numrows = count($results);
									        $x = 0;
											
											$bucketarray = array();
											$valuearray = array();
											
											$allarray = array();
											
											if($numrows>0){
												foreach($results as $r){
													//if ($x>0) {$bucketarray[$x-1] = $bucketarray[$x-1] . ' - ' . $r['bucket'];}
													$bucketarray[$x] = $r['bucket'];
													$valuearray[$x] = $r['COUNT'];
													$allarray[$x]['name'] = $r['bucket'];
													$allarray[$x]['y'] = $r['COUNT'];
													$x = $x + 1;
													}
											}
					                     	
					                     	$jso = implode(',',$bucketarray);
											$jso1 = implode(',',$valuearray);
											$jso2 = json_encode($allarray, JSON_NUMERIC_CHECK);
											//echo $jso;
											
					                     	
					                     	?>
					                     	
					                     	
					                     	 <script>
                                		
                                		
                                		$(function () { 
                                			
                                			var jso2 = <?php echo $jso2?>;
                                			//jso2 = JSON.stingify(jso2);
                                			console.log ('json2: ' + JSON.stringify(jso2));
										    
										    $('#field_details').highcharts({
										        chart: {
										            type: 'column'
										            
										        },
										        exporting: {
										        	enabled: false
										        },
										        credits: false,
										        title: {
										            text: ''
										        },
										        xAxis: {
										        	type: 'category'
										            
										        },
										        yAxis: {
										            title: {
										                text: 'Number of Responses'
										            }
										        },
										        series: [{
										            name: 'test',
										            data: jso2
										        }]
										    });
										});
                                		
                                		//categories: [<?php //echo $jso;?>]
                                		
                                	</script>     
					                     	
					                     	
					                     	
					                     	
					                     </div>   
					                        
					                        
					                        
					                        
                                        </div>
                                    </div>
                                    
                                    
                                		
                                	</div>
                                	
                                	
                                		
                                	</div>
                                		
                                		
                                		
                                		
                                		
                                		
                                		
                                	<!--	-->
                                	
                                	
                                	
                                	
                                	
                                	<div class="row">
                                	
                                	<div class="col-sm-6">
                                		 <div class="panel panel-default">
                                        <div class="panel-heading">
                                             <span class="label label-success pull-right">All Time</span>
					                        <h3>No. of Fields Completed</h3>
                                        </div>
                                        <div class="panel-body">
                                             <div id="container2" style="width: 100%; height: 250px; margin: 0 auto"></div>
					                        
                                        </div>
                                    </div>
                                    
                                    
                                		
                                	</div>
                                	
                                	<div class="col-sm-6">
                                		 <div class="panel panel-default">
                                        <div class="panel-heading">
                                             <span class="label label-success pull-right">All Time</span>
					                        <h3>Responses Completed by Field</h3>
                                        </div>
                                        <div class="panel-body">
                                             <div id="container3" class="contains-chart" style="width: 100%; height: 250px; margin: 0 auto"></div>
					                        
                                        </div>
                                    </div>
                                    
                                    <script>
                                		$(function () { 
										    $('#container3').highcharts({
										        chart: {
										            type: 'column'
										            
										        },
										        exporting: {
										        	enabled: false
										        },
										        credits: false,
										        title: {
										            text: ''
										        },
										        xAxis: {
										            categories: ['1', '2', '3','4','5','6']
										        },
										        yAxis: {
										            title: {
										                text: 'Number of Responses'
										            }
										        },
										        series: [{
										            name: 'Past 30 Days',
										            data: [24, 17, 15, 12, 10, 3],
										            color: '#26B79A'
										        }]
										    });
										});
                                		
                                		
                                	</script>
                                		
                                	</div>
                                		
                                	</div>
                                	
                                	
                                	<hr>
                                	
                                	<div class="row">
                                		<div class="col-lg-12">
                                			
                                			<div class="panel panel-default">
                                        <div class="panel-heading">
                                             
					                        <h3>All Instance Data</h3>
                                        </div>
                                        <div class="panel-body">
                                             <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                    <thead>
                    
                    <?php 
                        
                        
                        $SQL = "select CONCAT('Date.UTC(',date_format(dateCreated,'%y,%m,%d'),')') as created_date, COUNT(instanceID) AS Views from wb_roi_instance where YEAR(dateCreated) = '2017' AND MONTH(dateCreated) = '10' AND wbroiID=$wbappID group by created_date;";
                        
                        $list = $g->returnarray($SQL);
                      
						
						$numrows = count($list);
				        
						
						
						$x=0;
						if($numrows>0){
							foreach($list as $r){
								
								$vals = array();
								$vals[0]=$r['created_date'];
								$vals[1]=$r['Views'];
								$viewschart[$x]=$vals;
								$x=$x + 1;
								
								}
						}
						
						//print_r($viewschart);
						
						$viewschart1 = json_encode($viewschart, JSON_NUMERIC_CHECK);
                        
                        echo json_encode($viewschart, JSON_NUMERIC_CHECK);
                        
                        //$viewschart = $list;
                        
                        
                        
                        $SQL = "SELECT * FROM wb_dashboard_table_columns WHERE `wbroiID`=$wbappID ORDER BY columnOrder;";
                        
                        $list = $g->returnarray($SQL);
                        $ColSQL = '';
						
						$numrows = count($list);
				        $addfield = 0;
						$headers='';
						$ColSQL = '';
						
						$colarray = array();
						
						$time_start = microtime(true);
						if($numrows>0){
							foreach($list as $r){
								$addfield = $addfield + 1;
								$colarray[$addfield-1]=$r['fieldID'];
								//$ColSQL = $ColSQL . ', (SELECT formatted_value FROM wb_roi_instance_values t2 WHERE t2.instanceID=t1.instanceID AND t2.field=' . $r['fieldID'] . ') AS field' .  $addfield;
								$headers = $headers . '<th> ' . $r['columnHeader']   . '</th>';
								}
						}
						
						
                        $SQL = "SELECT *, 	
                        				DATE_FORMAT(t1.dateCreated,'%Y/%m/%d') InstanceDate, 
                        				DATE_FORMAT(t1.dateCreated,'%H:%i:%s') InstanceTime  
                        		
                        		FROM `wb_roi_instance` t1 
                        		JOIN `wb_roi_instance_values` t2 ON t2.instanceID=t1.instanceID
                        		WHERE `wbroiID`=$wbappID AND 
                        				t2.field IN (SELECT fieldID FROM wb_dashboard_table_columns WHERE wbroiID=$wbappID)
								ORDER BY t1.instanceID ASC, t2.field ASC;";
						
						
						$newSQL = "SELECT t1.instanceID ,
									DATE_FORMAT(t1.dateCreated,'%Y/%m/%d') InstanceDate,
									DATE_FORMAT(t1.dateCreated,'%H:%i:%s') InstanceTime,
									GROUP_CONCAT(t2.formatted_value SEPARATOR '/?/') AS instance_formatted_values,
									GROUP_CONCAT(t2.value SEPARATOR '/?/') AS instance_values,
									GROUP_CONCAT(t2.field SEPARATOR '/?/') AS calc_fields 
								   FROM `wb_roi_instance` t1 
								   JOIN `wb_roi_instance_values` t2 ON t2.instanceID=t1.instanceID
								   WHERE  `wbroiID`=$wbappID 
								   		AND CAST(t1.dateCreated AS DATE) BETWEEN '$startdatestng' AND '$currentdate'
                        				AND t2.field IN (SELECT fieldID FROM wb_dashboard_table_columns WHERE wbroiID=$wbappID)
								   GROUP BY t1.instanceID
								   ORDER BY t1.instanceID DESC";
						
						
						echo $newSQL;
						
						
		
						$list = $g->returnarray($newSQL);
						
						
						
						
						
                        $tablerows = '';
						
						$numrows = count($list);
				        $x = 0;
						
						//echo $SQL . '<br>' . $addfield;
						
						$time_start = microtime(true);
						
						$data_array = array();
						
						if($numrows>0){
							foreach($list as $r){
								$x = $x + 1;
								
								$instance_array = array();
								$fields			=array();
								$instance_values			=array();
								
								$fields 			= explode("/?/",$r['calc_fields']);
								$instance_values 	= explode("/?/",$r['instance_values']);
								
								//echo 'InstanceID: ' . $r['instanceID'] . '<br>';
								//print_r ($fields);
								
								//echo 'fields: (instanceID= ' . $r['instanceID'] . ')' . print_r($instance_values) . '';
								//print_r($fields);
								
								$z = 0;
								foreach($fields as $f){
									//echo 'count: ' . $z;
									//echo 'field: ' . $fields[$z];
									$instance_array[$fields[$z]] = $instance_values[$z];	
									$z = $z + 1;
								}
								
								$data_array[$r['instanceID']]=$instance_array;
								
								
								$y = 0;
								$tablerows = $tablerows .'<tr> ';
								$tablerows = $tablerows .'<td> ' . $x . '</td>';
								$tablerows = $tablerows .'<td> ' . $r['instanceID'] . '</td>';
								$tablerows = $tablerows .'<td> ' . $r['InstanceDate'] . '</td>';
								$tablerows = $tablerows .'<td> ' . $r['InstanceTime'] . '</td>';
								
								$count = 0;
								
								foreach($colarray as $cols){
									$givenval = '--';
									
									
									$givenval=$data_array[$r['instanceID']][$colarray[$count]];
									
									//$givenval = $colarray[$count];
									
									
									//foreach($fields as $f){
									//	if($f[$count]==$cols['fieldID'])
									//	{
									//		$givenval=$instance_values[$count];
									//		break;
									//	}
										$count = $count + 1;
									//}
									$tablerows = $tablerows .'<td> ' . $givenval . '</td>';
								}
								
								
									$tablerows = $tablerows .'</tr> ';
								}
						}
							

						$time_end = microtime(true);
						$execution_time = ($time_end - $time_start)/60;
						//echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
						
						//print_r($data_array);
						
                        ?>
                    
                    
                    
                    <tr>
                    	<th>#</th>
                    	<th> InstanceID</th>
                    	<th>Date</th>
                    	<th>Time</th>
                    	<?php echo $headers;?>
				    </tr>
                    </thead>
                    <tbody>
                    
                    <?php echo $tablerows;?>
                    </tbody>
                    </tfoot>
                    </table>
                        </div>
					                        
                                        </div>
                                    </div>
                                			
                                			
                                			
                                		</div>
                                		
                                	</div>
                                	
                            
                            <hr>
                            
                            
                            
                            <hr>
                            
                            <div class="row">
                            	
                            </div>
                          	
                             