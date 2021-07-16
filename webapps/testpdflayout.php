<?php
 
if (!isset($order)) die();
 
?>

<link rel='stylesheet' href='http://sandbox.theroishop.com/sandwebapp/assets/customwb/5/css/wbappID5.css'>
<link rel='stylesheet' href='sandwebapp/assets/css/bootstrap.min.css'>
<link rel='stylesheet' href='sandwebapp/assets/css/style.css'>

<style type="text/css">

/* cyrillic-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  src: local('Roboto'), local('Roboto-Regular'), url(http://fonts.gstatic.com/s/roboto/v15/ek4gzZ-GeXAPcSbHtCeQI_esZW2xOQ-xsNqO47m55DA.woff2) format('woff2');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  src: local('Roboto'), local('Roboto-Regular'), url(http://fonts.gstatic.com/s/roboto/v15/mErvLBYg_cXG3rLvUsKT_fesZW2xOQ-xsNqO47m55DA.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  src: local('Roboto'), local('Roboto-Regular'), url(http://fonts.gstatic.com/s/roboto/v15/-2n2p-_Y08sg57CNWQfKNvesZW2xOQ-xsNqO47m55DA.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  src: local('Roboto'), local('Roboto-Regular'), url(http://fonts.gstatic.com/s/roboto/v15/u0TOpm082MNkS5K0Q4rhqvesZW2xOQ-xsNqO47m55DA.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  src: local('Roboto'), local('Roboto-Regular'), url(http://fonts.gstatic.com/s/roboto/v15/NdF9MtnOpLzo-noMoG0miPesZW2xOQ-xsNqO47m55DA.woff2) format('woff2');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  src: local('Roboto'), local('Roboto-Regular'), url(http://fonts.gstatic.com/s/roboto/v15/Fcx7Wwv8OzT71A3E1XOAjvesZW2xOQ-xsNqO47m55DA.woff2) format('woff2');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  src: local('Roboto'), local('Roboto-Regular'), url(http://fonts.gstatic.com/s/roboto/v15/CWB0XYA8bzo0kSThX0UTuA.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}

 body {
 	font-family: 'Roboto', serif;
 	background-color: #ffffff;
 }

h3{
	font-family: 'Roboto';
	color: red;
}

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
<body>
<div class="container">
	<div class="row">
		<div class="col-sm-3">
			Col 1
		</div>
		<div class="col-sm-3">
			Col 2
		</div>
	</div>
	
	
	
</div>


<h3> Your C3 Reservations ROI Calculator Results1 </h3>
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
			<div class="panel panel-primary">
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
</body>