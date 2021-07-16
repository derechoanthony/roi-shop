
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
    	<input type="hidden" id="roiID" value="<?php echo $roiID; ?>"></input>
		<?php include 'analytics_dashboard.php';?>
 
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

</body>

</html>

<script>
	jQuery(document).ready(function($) {
	
	
	
	
	$('.viewcode').click(function(){
		console.log ('get code');
		
		var roiID = $('#roiID').val();
		//console.log ('roiID:' + roiID);
		$.ajax({
			type	: 	"POST",
			url		:	"ajax_getcodeview.php",
			data	:	'wbappID=' + roiID,
			success	:	function(returnhtml) {
				//console.log(returnhtml);
				
				$('#preview-panel').html(returnhtml);
				
				var editor_one = CodeMirror.fromTextArea(document.getElementById("code1"), {
                 lineNumbers: true,
                 matchBrackets: true,
                 styleActiveLine: true,
                 
             	});
				
			}
		

		
	});
		
		

	
	});
	

	
	
	$('body').on({
    
    click: function() {
        console.log ('save code');
		
		var roiID = $('#roiID').val();
		var htmlcode = $('#code1').val();
		console.log ('roiID:' + roiID);
		$.ajax({
			type	: 	"POST",
			url		:	"database.manipulation.php",
			data	:	'action=storeHtmlCode&wbappID=' + roiID + '&htmlcode=' + htmlcode,
			success	:	function(sql) {
				console.log(sql);
				console.log('save successful');
				
				
				
			}
		

		
	});
    }
},'#savecodebutton');
	
	
});	

$(document).ready(function() {
    
    console.log ('trying');
    
    var data1 = JSON.parse(<?php echo ($viewschart);?>);
	var data2 = [[Date.UTC(2013,5,2),1],
					[Date.UTC(2013,5,3),4],
					[Date.UTC(2013,5,4),4],
					[Date.UTC(2013,5,5),6],
					[Date.UTC(2013,5,6),3],
					[Date.UTC(2013,5,7),1],
					[Date.UTC(2013,5,9),0]];

	var timechart =     $('#timechart').highcharts({
            chart: {
                zoomType: 'x',
                events: {
                	load: function(event) {
              this.redraw();
              console.log(data1);
              console.log(data2);
          			},
                }
            },
            colors: ['#26B79A','#3191C5'],
            credits: {enabled: false},
            title: {
                text: ' '
            },
            
            xAxis: {
                type: 'datetime',
                gridLineWidth: 1
            },
            yAxis: {
                title: {
                    text: ''
                    
                }
            },
            legend: {
                enabled: false
            },
            

            series: [{
                type: 'areaspline',
                name: 'total views',
                color: 'rgba(38,183,154,0.1)',
                data: data1
            },
            {
                type: 'areaspline',
                name: 'total leads',
                color: 'rgba(49,145,197,0.1)',
                
                data: data2
            }]
        });
  
    
    
});

// fix dimensions of chart that was in a hidden element
jQuery(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function (e) { // on tab selection event
    jQuery( ".contains-chart" ).each(function() { // target each element with the .contains-chart class
        var chart = jQuery(this).highcharts(); // target the chart itself
        //chart.destroy();
        chart.redraw();
        chart.reflow(); // reflow that chart
    });
})

	 
</script>


<script>
        $(document).ready(function() {
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


        });

        
        
        // Change the Time Interval to the specified time clicked.
        
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


<script>
                                		$(function () {

											console.log('trying chart');
										    // Create the chart
										   var containerchart = $('#container').highcharts({
										    
										   
										        exporting: {
										        	enabled: false
										        },
										        credits: false,
													
										        chart: {
										            type: 'pie'
											        
										        },
										        title: {
										        	enabled: false,
										            text: '45%',
										            align: 'center',
										            verticalAlign: 'middle',
										            y:10,
										            style: {
										                color: '#dce0df',
										                fontWeight: 'bold',
										                fontSize: '25px'
										            }
										        },
										        plotOptions: {
										            pie: {
										            	size: '100%',
										                innerSize: '70%'
										            }
										        },
										        series: [{
										            data: [{
										                y: 45/1,
										                color: '#A3E1D4'
										            }, {
										                y: 100-45/1,
										                color: '#dce0df'
										            }],
										            dataLabels: {
										                enabled: false
										            }
										        }]
										   	
										    
										    
										    });
										
										
										});
                                		
                                		
                                	</script>
                                	
                                	<script>
                                		$(function () {

											console.log('trying chart');
										    // Create the chart
										    $('#container1').highcharts({
										    
										   
										        exporting: {
										        	enabled: false
										        },
										        credits: false,
													
										        chart: {
										            type: 'pie'
											        
										        },
										        title: {
										        	enabled: false,
										            text: '25%',
										            align: 'center',
										            verticalAlign: 'middle',
										            y:10,
										            style: {
										                color: '#dce0df',
										                fontWeight: 'bold',
										                fontSize: '25px'
										            }
										        },
										        plotOptions: {
										            pie: {
										            	size: '100%',
										                innerSize: '70%'
										            }
										        },
										        series: [{
										            data: [{
										                y: 25/1,
										                color: '#12547D'
										            }, {
										                y: 100-25/1,
										                color: '#dce0df'
										            }],
										            dataLabels: {
										                enabled: false
										            }
										        }]
										   	
										    
										    
										    });
										});
                                		
                                		
                                	</script>
                                	
                                	<script>
                                		$(function () { 
										    $('#container2').highcharts({
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
										                text: 'Percent of Views'
										            }
										        },
										        series: [{
										            name: 'Past 30 Days',
										            data: [90, 85, 82,75,50,10],
										            color: '#12547D'
										        }, {
										            name: 'Total',
										            data: [85, 82, 81,80,50,25],
										            color: '#9CC3DA'
										        }]
										    });
										});
                                		
                                		
                                	</script>
                                	
                                	
                                	<script>
                                		$(function () { 
                                			
                                			console.log('trying new chart';)
                                			
										    $('#timechart1').highcharts({
										        
										        chart: {
											        type: 'spline'
											    },
											    title: {
											        text: 'Snow depth at Vikjafjellet, Norway'
											    },
											    subtitle: {
											        text: 'Irregular time data in Highcharts JS'
											    },
											    xAxis: {
											        type: 'datetime',
											        dateTimeLabelFormats: { // don't display the dummy year
											            month: '%e. %b',
											            year: '%b'
											        },
											        title: {
											            text: 'Date'
											        }
											    },
											    yAxis: {
											        title: {
											            text: 'Snow depth (m)'
											        },
											        min: 0
											    },
											    tooltip: {
											        headerFormat: '<b>{series.name}</b><br>',
											        pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
											    },
											
											    plotOptions: {
											        spline: {
											            marker: {
											                enabled: true
											            }
											        }
											    },
											
											    series: [{
											        name: 'Winter 2012-2013',
											        // Define the data points. All series have a dummy year
											        // of 1970/71 in order to be compared on the same x axis. Note
											        // that in JavaScript, months start at 0 for January, 1 for February etc.
											        data: [
											            [Date.UTC(1970, 9, 21), 0],
											            [Date.UTC(1970, 10, 4), 0.28],
											            [Date.UTC(1970, 10, 9), 0.25],
											            [Date.UTC(1970, 10, 27), 0.2],
											            [Date.UTC(1970, 11, 2), 0.28],
											            [Date.UTC(1970, 11, 26), 0.28],
											            [Date.UTC(1970, 11, 29), 0.47],
											            [Date.UTC(1971, 0, 11), 0.79],
											            [Date.UTC(1971, 0, 26), 0.72],
											            [Date.UTC(1971, 1, 3), 1.02],
											            [Date.UTC(1971, 1, 11), 1.12],
											            [Date.UTC(1971, 1, 25), 1.2],
											            [Date.UTC(1971, 2, 11), 1.18],
											            [Date.UTC(1971, 3, 11), 1.19],
											            [Date.UTC(1971, 4, 1), 1.85],
											            [Date.UTC(1971, 4, 5), 2.22],
											            [Date.UTC(1971, 4, 19), 1.15],
											            [Date.UTC(1971, 5, 3), 0]
											        ]
											    }, {
											        name: 'Winter 2013-2014',
											        data: [
											            [Date.UTC(1970, 9, 29), 0],
											            [Date.UTC(1970, 10, 9), 0.4],
											            [Date.UTC(1970, 11, 1), 0.25],
											            [Date.UTC(1971, 0, 1), 1.66],
											            [Date.UTC(1971, 0, 10), 1.8],
											            [Date.UTC(1971, 1, 19), 1.76],
											            [Date.UTC(1971, 2, 25), 2.62],
											            [Date.UTC(1971, 3, 19), 2.41],
											            [Date.UTC(1971, 3, 30), 2.05],
											            [Date.UTC(1971, 4, 14), 1.7],
											            [Date.UTC(1971, 4, 24), 1.1],
											            [Date.UTC(1971, 5, 10), 0]
											        ]
											    }, {
											        name: 'Winter 2014-2015',
											        data: [
											            [Date.UTC(1970, 10, 25), 0],
											            [Date.UTC(1970, 11, 6), 0.25],
											            [Date.UTC(1970, 11, 20), 1.41],
											            [Date.UTC(1970, 11, 25), 1.64],
											            [Date.UTC(1971, 0, 4), 1.6],
											            [Date.UTC(1971, 0, 17), 2.55],
											            [Date.UTC(1971, 0, 24), 2.62],
											            [Date.UTC(1971, 1, 4), 2.5],
											            [Date.UTC(1971, 1, 14), 2.42],
											            [Date.UTC(1971, 2, 6), 2.74],
											            [Date.UTC(1971, 2, 14), 2.62],
											            [Date.UTC(1971, 2, 24), 2.6],
											            [Date.UTC(1971, 3, 2), 2.81],
											            [Date.UTC(1971, 3, 12), 2.63],
											            [Date.UTC(1971, 3, 28), 2.77],
											            [Date.UTC(1971, 4, 5), 2.68],
											            [Date.UTC(1971, 4, 10), 2.56],
											            [Date.UTC(1971, 4, 15), 2.39],
											            [Date.UTC(1971, 4, 20), 2.3],
											            [Date.UTC(1971, 5, 5), 2],
											            [Date.UTC(1971, 5, 10), 1.85],
											            [Date.UTC(1971, 5, 15), 1.49],
											            [Date.UTC(1971, 5, 23), 1.08]
											        ]
											    }]
										        
										        
										    });
										});
                                		
                                		
                                	</script>
