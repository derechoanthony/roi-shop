		
		<script src="/assets/js/jquery/jquery-2.1.1.js"></script>
		<script src="/assets/js/jquery/jquery-ui.min.js"></script>
		<script src="/assets/js/bootstrap/bootstrap.min.js"></script>
		<script src="/assets/js/tooltipster/jquery.tooltipster.min.js"></script>
		<script src="/assets/js/chosen/chosen.jquery.js"></script>
		<script src="/assets/js/slider/nouislider.js"></script>
		<script src="/assets/js/highcharts/highcharts.js"></script>
		<script src="/assets/js/calx/jquery-calx-2.1.1.js"></script>
		<script src="/assets/js/calx/numeral.js"></script>
		<script src="/assets/js/roishop/jquery.roifunctions.js"></script>
		<script src="/assets/js/sticky/jquery.sticky.js"></script>
		<script src="/assets/js/video/video.functions.js"></script>
		<script src="/assets/js/datatables/jquery.dataTables.min.js"></script>
		<script src="/assets/js/metis-menu/jquery.metisMenu.js"></script>
		<script src="/assets/js/magnific-popup/jquery.magnific-popup.min.js"></script>
		<script src="/assets/js/languages/languages.js"></script>
		<script src="/assets/js/enterprise/setup.plugins.js"></script>
		<script src="/assets/js/enterprise/roishop.initialize.js"></script>
		<script src="/assets/js/enterprise/roishop.modal.js"></script>
		<script src="/assets/js/roishop/jquery.roishop.js"></script>
		<script src="/assets/js/roishop/jquery.roibuilder.js"></script>
		
		<script>

      $(document).ready(function() {
        $('.popup-with-form').magnificPopup({
          type: 'inline',
          preloader: false,
          focus: '#name',

          // When elemened is focused, some mobile browsers in some cases zoom in
          // It looks not nice, so we disable it:
          callbacks: {
            beforeOpen: function() {
              if($(window).width() < 700) {
                this.st.focus = false;
              } else {
                this.st.focus = '#name';
              }
            }
          }
        });
      });
    	
		</script>		
		
		<!--<script src="js/numeral/languages/languages.js"></script>
		<script src="js/scroll/smooth-scroll.js"></script>
		<script src="js/iChecks/icheck.min.js"></script>-->
		
		<?php require_once("php/languages.php"); ?>
	
		
	</body>
	
</html>