<?php include '../inc/reflect-head.php'; 
	$wbappID 	= $_GET['wbappID'];
	if(isset($_GET['key'])) {
    $wbappkey 	= $_GET['key'];
	}else{$wbappkey=0;}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>INSPINIA - Landing Page</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body id="page-top" class="landing-page">

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>	
	
<div class="navbar-wrapper">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="http://www.theroishop.com"><img src="assets/img/logo.png"/></a>
                </div>
                
            </div>
        </nav>
</div>

<section id="features" class="container services">
<div class="row">
	<div class="col-lg-12">
		
        <div class="panel panel-default">
            <div class="panel-heading">
            	<div class="row">
            	<div class="col-lg-12">	
                <span class="pull-right">
                	
                	<div class="fb-like btn btn-w-m btn-sm" data-href="https://www.theroishop.com/webapp/reflector.php?wbappID=<?php echo $wbappID;?>&key=<?php echo $wbappkey?>&source=1" data-layout="button" data-action="like" data-size="large" data-show-faces="true" data-share="false">
                		
                	</div>
                	
                	<button type="button" class="btn btn-w-m white-bg btn-sm btn-outline btn-default">Share...</button>
                </span>
                
                <?php echo '<strong><h3>' . $g->DLookup('roiName','wb_roi_list','wb_roi_ID=' . $wbappID) . '</h3></strong>';?>
            </div>
            </div>
            </div>
            <div class="panel-body">
                <!-- Get the correct calculator -->
              <?php $wbappID 	= $_GET['wbappID'];
            		$key 		= $_GET['key'];	
            		$iframesrc	= '../icalc.php?wbappID=' . $wbappID . '&key=' . $key; 
            		$height		= $g->DLOOKUP('height','wb_roi_settings','wb_roi_ID=' . $wbappID);
            		$width		= $g->DLOOKUP('width','wb_roi_settings','wb_roi_ID=' . $wbappID);
            		
            		?>	
            <iframe width="<?php echo $width;?>px" height="<?php echo $height;?>px" frameborder="0" src="<?php echo $iframesrc ?>"></iframe>	
               
               
            </div>

        </div>
                                
	</div>
</div>
</section>




<!-- Mainly scripts -->
<script src="../assets/js/jquery-2.1.1.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="../assets/js/inspinia.js"></script>
<script src="../assets/js/plugins/pace/pace.min.js"></script>
<script src="../assets/js/plugins/wow/wow.min.js"></script>


<script>

    $(document).ready(function () {

        $('body').scrollspy({
            target: '.navbar-fixed-top',
            offset: 80
        });

        // Page scrolling feature
        $('a.page-scroll').bind('click', function(event) {
            var link = $(this);
            $('html, body').stop().animate({
                scrollTop: $(link.attr('href')).offset().top - 50
            }, 500);
            event.preventDefault();
            $("#navbar").collapse('hide');
        });
    });

    var cbpAnimatedHeader = (function() {
        var docElem = document.documentElement,
                header = document.querySelector( '.navbar-default' ),
                didScroll = false,
                changeHeaderOn = 200;
        function init() {
            window.addEventListener( 'scroll', function( event ) {
                if( !didScroll ) {
                    didScroll = true;
                    setTimeout( scrollPage, 250 );
                }
            }, false );
        }
        function scrollPage() {
            var sy = scrollY();
            if ( sy >= changeHeaderOn ) {
                $(header).addClass('navbar-scroll')
            }
            else {
                $(header).removeClass('navbar-scroll')
            }
            didScroll = false;
        }
        function scrollY() {
            return window.pageYOffset || docElem.scrollTop;
        }
        init();

    })();

    // Activate WOW.js plugin for animation on scrol
    new WOW().init();

</script>

</body>
</html>
