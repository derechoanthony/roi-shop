$(document).ready(function() {
	
	buildChart();
	
});

function buildChart() {
	
	$('.ROICalcElemID').each(function() {

		var roiElemId = $(this).attr('data-id');

		$.getJSON('construct/charting/chartoptions.php?ROICalcElemID=' + roiElemId)
			.done(function(json) {
				console.log(json);
				chart = new Highcharts.Chart(
					json
				);
				updateChart();
			}).fail( function( jqxhr, textStatus, error ){
				var err = textStatus + ", " + error ;
				console.log( "Request Failed: " + err );
			});
	});
}