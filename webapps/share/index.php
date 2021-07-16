
<?php include '../inc/head.php'; 
$roiID = $_GET['wbappID'];
$key 	= $_GET['key'];
$tabname='share';
?>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>My WebApps</h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.php">Home</a>
            </li>
             <li>
                <a href="list.php">WebApp List</a>
            </li>
           
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
		<?php include '../inc/detailsmenu.php';?>
	</div>
</div>
	
<div class="row">
<div class="col-sm-12">
    	<input type="hidden" id="roiID" value="<?php echo $roiID; ?>"></input>
		<?php include 'details_tab_share.php';?>
 
            </div>


</div>
            
        </div>

<?php include '../inc/footer.php';?>

    <!-- CodeMirror -->
    <script src="../assets/js/plugins/codemirror/codemirror.js"></script>
    <script src="../assets/js/plugins/codemirror/mode/javascript/javascript.js"></script>

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
    
    var data1 = [[Date.UTC(2013,5,2),6],
					[Date.UTC(2013,5,3),8],
					[Date.UTC(2013,5,4),24],
					[Date.UTC(2013,5,5),56],
					[Date.UTC(2013,5,6),34],
					[Date.UTC(2013,5,7),10],
					[Date.UTC(2013,5,9),15]];
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
              console.log('done redrawing');
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
