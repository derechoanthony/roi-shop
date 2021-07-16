<?php 

$basepath = $_SERVER['DOCUMENT_ROOT'];

// include autoloader
require_once 'core/functions/dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);

// instantiate and use the dompdf class
$dompdf = new Dompdf($options);
$dompdf->set_base_path($basepath);

//$dompdf->set_base_path("localhost/sandwebapp/");
//$dompdf->setOptions($options);

//$dompdf->output();

	$roihtml1 = '
<div class="container">	
<h3> Your C3 Reservations ROI Calculator Results </h3>
<table>
	<tr>
		<td>
			This is the first column
		</td>
		<td>
			<ul>
				<li> Number of inbound appointments received per week:  400</li>
				<li> Number of inbound appointments received per week:  400</li>
			</ul>
			<div class="panel panel-default">
                <div class="panel-heading">
                    Default Panel
                </div>
                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                </div>

            </div>
		</td>
	</tr>
	
</table>


</div>	
	';


	$roihtml = '<html><body class="calculator-body">' . $roihtml1 . ' </body></html>';
	
	$roitestCSS = '';
	
	$roiCSS1 = '<link href="sandwebapp/assets/css/font-awesome.min.css" rel="stylesheet">
<link href="sandwebapp/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="sandwebapp/assets/customwb/5/css/wbappID5.css" rel="stylesheet">

<style type="text/css">

  .contact {
   background-color: #b6c2c5;
   color: #154d54;
   padding: 10px;
  }
  
 .disclaimer {
  margin: 5px;  
  font-size: medium;
   color: #203041;
  }
  
  .total-box {
   background-color: #203041; 
  }

	.total-box h4{
     color: #ffffff;
     font-size: xx-large; 
    }

.btn {
    
    /*Step 2: Basic Button Styles*/
    display: block;
    background: #5bd9b3;
    border: 3px solid rgba(24, 77, 86, 1);
    
    /*Step 3: Text Styles*/
    color: #ffffff;
    font-size: large; 
    text-align: center;
    
    /*Step 4: Fancy CSS3 Styles*/
    //background: -webkit-linear-gradient(top, #34696f, #2f5f63);
    //background: -moz-linear-gradient(top, #34696f, #2f5f63);
    //background: -o-linear-gradient(top, #34696f, #2f5f63);
    //background: -ms-linear-gradient(top, #34696f, #2f5f63);
    //background: linear-gradient(top, #34696f, #2f5f63);
    
    -webkit-border-radius: 10px;
    -khtml-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    
    //-webkit-box-shadow: 0 8px 0 #1b383b;
    //-moz-box-shadow: 0 8px 0 #1b383b;
    //box-shadow: 0 8px 0 #1b383b;
    
    //text-shadow: 0 2px 2px rgba(255, 255, 255, 0.2);
    
}

/*Step 3: Link Styles*/
a.button {
    text-decoration: none;
}

/*Step 5: Hover Styles*/
a.button:hover {
    background: #3d7a80;
    //background: -webkit-linear-gradient(top, #3d7a80, #2f5f63);
    //background: -moz-linear-gradient(top, #3d7a80, #2f5f63);
    //background: -o-linear-gradient(top, #3d7a80, #2f5f63);
    //background: -ms-linear-gradient(top, #3d7a80, #2f5f63);
    //background: linear-gradient(top, #3d7a80, #2f5f63);
}

.hiddenfields {
 display: none; 
}

</style>



	';
	
	$roiCSS		= '<!DOCTYPE html><html><head> ' . $roiCSS1 . ' <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15"></head>';
	
	
	
	$roi		= $roiCSS . $roihtml;
	//$roi		= $roihtml;


$html = <<<'ENDHTML'
<html>
 <body>
  <h1>Hello Dompdf</h1>
 </body>
</html>
ENDHTML;



$htmlfile = "http://www.c3solutions.com/";

$order = array("name" => "Ivan Dimov", "productName" => "Waterproof portable speakers", "productPrice" => "20", "deliveryDate" => "2150");


ob_start();
 
require_once("testpdflayout.php");
 
$template = ob_get_clean();



$dompdf->loadHtml($template);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();


?>