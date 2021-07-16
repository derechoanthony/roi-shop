<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	require_once("$root/db/db_connection.php");
	require_once("$root/db/db_interaction.php");
	
	$roi_information = new db_interaction($db);

	require_once( "$root/webapps/mpdf/mpdf.php" );

	$reportCSS 	= '<link href="../assets/css/font-awesome/font-awesome.css" rel="stylesheet">
<link href="../assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">


<style>
	@page {
		margin-top: 75px;
		margin-bottom: 1cm;
		margin-left: 1cm;
		margin-right: 1cm;
		footer: html_nofooter;
		//background-image: url("http://www.theroishop.com/webapps/assets/customwb/10013/img/pdf_back1.png");
        
	}



@page page1 {
		margin-top: 0.85cm;
		margin-bottom: 1cm;
		margin-left: 0.5cm;
		margin-right: 0cm;
  		header: html_letterheader1;
		footer: nofooter;
		background-image: url("http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lmn-page1back.png");
        background-image-resize: 6;
	}

@page page2 {
		margin-top: 50px;
		margin-bottom: 1cm;
		margin-left: 1cm;
		margin-right: 1cm;
		footer: html_letterfooter2;
		background-image: url("http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lgm_back_page2_1.png");
  	background-image-resize: 6;		
  //background-repeat: repeat-y;
        
        
	}
@page page3 {
		margin-top: 50px;
		margin-bottom: 1cm;
		margin-left: 1cm;
		margin-right: 1cm;
		footer: html_letterfooter2;
		//background-image: url("http://www.theroishop.com/enterprise/company_specific_files/491/img/page3back1.png");
        //background-image-resize: 6;
        
	}

@page page7 {
		margin-top: 50px;
		margin-bottom: 1cm;
		margin-left: 1cm;
		margin-right: 1cm;
		footer: nofooter;
		background-image: url("http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lgm_back_page7_1.png");
        //background-image-resize: 6;
        
	}


	.pdfcontent {
     font-family: \'Fira Sans\', sans-serif; 
    padding-left: 40px;
    padding-right: 40px; 
    }

.page1 {
    		height:210mm;
            width:297mm;
            //page-break-before:always;
          page: page1;
    }
 
 	.page2
        {
            height:210mm;
            width:297mm;
            //page-break-before:always;
          page: page2;
        }
 
 .page3
        {
            height:210mm;
            width:297mm;
            //page-break-before:always;
          page: page3;
        }
 
 .page7
        {
            height:210mm;
            width:297mm;
            //page-break-before:always;
          page: page7;
        }


  .pdfcontent h2 {
   color: #56565A;
   font-size: 18px;
   margin-top: 20px;
    padding-top: 20px;
    padding-bottom: 20px;
  }

  .pdfcontent h3 {
    color: #56565A;
    font-size: 16px;
    margin-top: 30px;
    font-weight: bold;
    margin-bottom: 5px;
  }

  .pdfcontent p {
   font-size: 14px;
    padding-bottom: 3px;
    color: #56565A;
    padding-left:0px;
  }

	.page
        {
            height:210mm;
            width:297mm;
            page-break-after:always;
        }

	.maintitle {
    padding-top: 300px;
    padding-left: 50px;
    padding-bottom: 150px;
    color: #FFFFFF;
    }

	.frontcredits {
    color: white;
    padding-left: 50px;
    padding-bottom: 0px;
    margin-bottom: 0px;
    }

	.finalwording {
    color: #FFFFFF;
    font-size: 16px;
    padding-left: 50px;
    padding-top: 35px;
    }

	.logowording {
    color: #095280;
    text-size: 16px;
    padding: 50px;  
    }

	.bluetitle {
    color: #095280;
    text-size: 16px;
 	padding-left: 20px;
    }
 
	.costamount {
    padding-bottom: 50px;
      text-align: center;
    }

.costamount1 {
    padding-bottom: 10px;
      text-align: center;
    }

	.blueback {
    background-color: #095280
    border-top-left-radius: 4em;
      padding: 10px;
    }
 
	.bluetable td{
    color: #FFFFFF;
    text-size: 12px;
    padding-bottom: 10px;
    }

	.bluerounded {
 border:0.1mm solid #220044;
 background-color: #095280;
 //background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
 border-radius: 4mm;
 background-clip: border-box;
 padding: 1em;
   margin-bottom: 15px;
}

	.smalltext {
    text-size: 8px;
    }

	.lightbluebox {
    background-color: #252536;
    color: #FFFFFF;
    padding: 10px;
    margin-top: 35px;
    margin-bottom: 35px;
    }

	.lightbluebox1 {
    background-color: #252536;
    color: #FFFFFF;
    padding: 10px;
    margin-top: 15px;
    margin-bottom: 15px;
    }

	.italic {
    font-style: italic;
    }
	
	.wording {
    padding-top: 35px;
    padding-bottom: 35px;
    }

	.wording1 {
    padding-top: 10px;
    padding-bottom: 10px;
    }

	td.rightborder {
    border-right: 1px solid #000000;
    }

	#comment_table {
	margin-top: 10px;
	}
    
    #comment_table td {
	padding: 25px;
	}
    
    #lightblue td {
	background-color: #252536;
    color: #FFFFFF;
    padding: 10px;
	}

    .right {
    position: absolute;
    right: 0px;
    width: 300px;
    padding: 10px;
}

	.rightalign {
    text-align: right;
    }

	.centeralign{
    text-align: center;
    }

.center {
    margin: auto;
    padding: 10px;
}
    
	.pagewidth {
    width: 100%;
    }

	#regtable {
    width: 100%;
     font-size: 18px;
    background-color: #FFFFFF;
    border: 2px solid #D9D9D9;
    }
	
	#regtable td, #regtable th {
      font-size: 20px;
    border-bottom: 1px solid #D9D9D9;
    
    #regtable td {
	text-align: right;
	}
    
    .disclaimer {
    font-size: 10px;
    font-style: italic;
    }

    .shiftright {
    padding-left: 50px;
    }

	.shiftright25 {
    margin-top: 0px;
    padding-top: 0px;
    padding-left: 25px;
      //border: 1px solid red;
    }

 <!-- Start Old CSS -->
 
 
	.logo {
      margin: 20px;
    padding: 15px;
    }

	.h2toptitle {
    font-size: 20px;
      color: #72BF44;
    }

	.subh3 {
      font-weight: normal;
      font-style: italic;
    }

	.toptable {
    width: 2200px;
    //border: 1px solid #F57E20;
    }

	.toptable th{
    background-color:#FFFFFF;
    font-size: 11px;
     color: #000000;
     text-align:center;
      padding: 0px;
      padding-bottom: 5px;
    }

	.whitelink a {
     color: #000000;
    }

	.toptable td {
    background-color: #FDE5D2;
    font-size: 12px;
    color: #56565A;
    border: 1px solid #F57E20;
    //padding: 10px;
      vertical-align: top;
     text-align:center; 
    }

	#logotable {
    width: 100%;
	text-align: center;
	}
    
    #logotable td {
	padding: 20px;
	}

	#greytable {
    width: 100%;
    background-color: #DCDCDC;
    color: #666666;
    text-align: center;
	vertical-align: top;
	}
    #greytable td{
    padding-top: 10px;
    background-color: #DCDCDC;
    color: #666666;
    border-right: 4px solid #FFFFFF;
    border-left: 4px solid #FFFFFF;
    text-align: center;
	font-size: 12px;
	font-weight: bold;
	}
    
     #greytable td.bottom{
     padding-top: 15px;
    padding-bottom: 15px;
    font-size: 18px;
	font-weight: lighter;
	}
    
    #greentable {
    width: 100%;
    background-color: #72BF44;
    color: #FFFFFF;
    text-align: center;
	vertical-align: top;
	}
    #greentable td{
    padding-top: 15px;
    background-color: #72BF44;
    color: #FFFFFF;
    border-right: 5px solid #FFFFFF;
    border-left: 5px solid #FFFFFF;
    text-align: center;
	font-size: 12px;
	font-weight: normal;
	}
    
     #greentable td.bottom{
     padding-top: 20px;
    padding-bottom: 20px;
    font-size: 20px;
	font-weight: normal;
	}
    
     #footerinfo {
     margin-top: 15px;
	margin-left: 5px;
	margin-right: 5px;
    width: 100%;
    background-color: #FFFFFF;
    color: #CCCCCC;
    border: 1px solid #CCCCCC;
    text-align: left;
	vertical-align: top;
	font-size: 9px;
	}
    
     #footerinfo td {
     padding: 15px;
    
    background-color: #FFFFFF;
    color: #9C99A3;
   
    text-align: left;
	vertical-align: top;
	font-size: 9px;
	}
   
     #footerinfo td.sourcerow{
     padding-top: 25px;
    
    
	}

    #wordtable {
     margin-top: 10px;
	margin-left: 5px;
	margin-right: 5px;
    width: 100%;
    background-color: #FFFFFF;
    color: #000000;
    
    text-align: left;
	vertical-align: top;
	font-size: 8px;
	}
    
     #wordtable td {
      padding: 5px;
    width: 100%;
    background-color: #FFFFFF;
    color: #000000;
    border: 0px solid #CCCCCC;
    text-align: center;
	vertical-align: top;
	font-size: 12px;
	}
    
    
    #currentcosttable {
    margin-top: 10px;
	margin-left: 5px;
	margin-right: 5px;
    width: 100%;
    background-color: #999999;
    color: #FFFFFF;
    text-align: center;
	vertical-align: middle;
	font-size: 12px;
	}
    
    .titlerow, .costline, .titlerow1, .costline1 {
      padding: 10px;
      padding-top: 30px;
      
    }

	#currentcosttable td.costline {
    font-weight: bold;
	font-size: 18px;
    padding-bottom: 20px;
	border-bottom: 1px dotted #FFFFFF;
	}
    
    #currentcosttable td.titlerow {
    font-weight: bold;
    padding-top: 18px;
	font-size: 18px;
	}
    
    #currentcosttable td {
	padding: 5px;
    width: 100%;
	}
    
    #currentcosttable td.largefont {
	font-size: 40px;
	color:#BDBDBD;
	}

    
    #calculatetable {
    margin-top: 10px;
	margin-left: 5px;
	margin-right: 5px;
    width: 100%;
    background-color: #FFFFFF;
    color: #999999;
    border: 1px solid #999999;
    text-align: center;
	vertical-align: top;
	font-size: 10px;
	border-collapse:separate;
	}
    
    #calculatetable td.titlerow {
    color: #666666;
    font-size: 15px;
	font-weight: bold;
	}
    
    #calculatetable td.largefont {
	font-size: 40px;
	color:#BDBDBD;
    vertical-align: middle;
	}
    
	#calculatetable td.table-cell {
     border: 1px dashed #999999;
     padding: 10px;
	}
    
    #calculatetable td.divider {
     border-top: 1px solid #999999;
     margin: 10px;
	}
     #calculatetable td.spacer {
     
     padding: 10px;
	}
    
    .costtitle {
    font-size: 14px;
    }
    
    .bold {
    font-weight: bold;
    }
    
    .innertable {
    border: 1px dashed #999999;
	margin-left: 10px;
	padding-left: 20px;
    text-align: center;
    vertical-align: top;
      height: 400px;
      width: 100%;
    }
    
	.lineheight2 {
    line-height: 2;
    
    }

    
	.celltitle {
     color: #000000;
    font-weight: bold;
    padding-top: 10px;
    }

	
	#futurecosttable {
    margin-top: 10px;
	margin-left: 5px;
	margin-right: 5px;
    width: 100%;
    background-color: #72BF44;
    color: #FFFFFF;
    text-align: center;
	vertical-align: middle;
	}
    
     #futurecosttable td {
    font-size: 18px;
	padding: 5px;
    width: 100%;
	}
    
    #futurecosttable td.titlerow, td.costline {
      padding: 10px;
      padding-top: 30px;
    }

	#futurecosttable td.costline {
    font-size: 24px;
    padding-bottom: 20px;
	border-bottom: 1px dotted #CCCCCC;
    font-weight: bold;
	}
    
    #futurecosttable td.titlerow {
    font-size: 24px;
    padding-top: 20px;
	font-weight: bold;
	}
    
   
    
    #futurecosttable td.largefont {
	font-size: 40px;
	color:#CCCCCC;
	}


    ul {
    list-style: disc outside none; 
    margin-left: 0; 
    padding-left: 10px;
}
li {
    padding-left: 10px;
}
    

	#finaltable {
	 margin-top: 25px;
	margin-bottom: 10px;
    width: 100%;
    background-color: #666666;
    color: #FFFFFF;
    text-align: center;
	vertical-align: middle;
	font-size: 18px;
	
	}

    #finaltable td.top {
    padding: 5px;
	padding-top: 20px;	
	}
    
    #finaltable td.bottom {
    padding: 5px;
	padding-bottom: 20px;	
	}

    
	#table1 {
      //width: 800px;
      background-color:#FFFFFF;
      font-size: 11px;
      color: #000000;
      
      padding-top: 0px;
      padding-bottom: 5px;
	}

    #table1 th {
	 color: #56565A;
	}
    
    #table1 td {
	background-color:#FFFFFF;
    color: #56565A;
    font-size: 11px;
	border: 0px solid #F57E20;
    padding-left: 30px;
	padding-right: 30px;
	}
    
	.toptable .bold_orange {
      font-size: 18px;
      color: #F57E20;
      font-weight: bold;
    }

	.toptable .orangeback {
      background-color: #F57E20;
      font-size: 12px;
      color: #FFFFFF;
      font-weight: bold;
    }

	.toptable .small_bold {
      font-size: 12px;
      color: #56565A;
    }

	.toptable .med_bold {
      font-size: 16px;
      color: #56565A;
      font-weight: bold;
    }

	.toptable .border_right {
     border-right: 1px solid #000000;
    }

	.lineheight {
    font-size: 8px;
    padding: 0px;
    }

	 .toptable .mainreturn {
      font-size: 50px;
       text-align: left;
       font-weight: normal;
       border: 1px solid #F57E20;
    }

	.toptable .returntext {
      font-size: 16px;
      padding-left: 20px;
      padding-top: 20px;
      padding-bottom: 20px;
      line-height: 200%;
      font-weight: normal;
      text-align: left;
      border: 1px solid #F57E20;
    }

	#table2 {
    width: 2200px;
    }
    
	

	#table2 td {
    text-align:center;
    font-size: 13px;
    font-weight: bold;
    color: #FFFFFF;
    background-color: #F57E20;
    padding: 5px;
    }

	#table3 th {
	background-color: #FFFFFF;
    color: #56565A;
    font-size: 16px;
	padding: 5px;
	}
    
    #table3 tr.white {
	background-color: #FFFFFF;
	}

	#table4 {
    width: 1000px;
    border-top: 1px solid #000000;
    border-bottom: 1px solid #000000;
    color: #56565A;
    text-align:center;
    }
    
    #table4 td {
	padding: 15px;
	//font-style: italic;
	font-size: 11px;
	}
    
    #table9 th{
	background-color: #F57E20;
    color: #FFFFFF;
    border: 1px solid #F57E20;
    }

    #table5 {
	width: 400px;
	}
    
    #table5 th{
    background-color: #F57E20;
    color: #FFFFFF;
    border: 1px solid #F57E20;
    padding: 20px;
    font-size: 15px;
	text-align: center;
	font-weight: normal;
    }
    

    
    .strong {
	font-weight: bold;
	}

	#table6 {
	padding-top: 50px;
	}

	#table6 td, h3 {
	color: #56565A;
    font-size: 16px;
    margin-top: 30px;
    font-weight: bold;
    margin-bottom: 5px;
	}

    #table6 td, p{
    font-size: 15px;
    font-weight: normal;
    padding-bottom: 3px;
    color: #56565A;
}

    #table7 td, p{
    font-size: 15px;
    font-weight: normal;
    padding-bottom: 3px;
    color: #56565A;
    padding-left: 20px;
}
    
	#table8 {
	 border: 1px solid #000000;
     
}

	#table8 th {
	-webkit-transform: rotate(-90deg); 
                -moz-transform: rotate(-90deg);
}

	#table8 td {
	background-color: #FFFFFF;
    border: 1px solid #000000;
    text-align: left;
	padding: 8px;
	}
	
  .whitetext p{
   color: #FFFFFF; 
  }

	.shadow{
   background: rgba(225,225,10,0.55);
      border-radius: 6px;
   margin: 3px;
      padding: 3px;
    }

  .totalbox {
   background-color:  #FFFFFF;
   //-webkit-box-shadow: 0 0 10px #efa63e; 
    //-moz-box-shadow: 0 0 10px #efa63e; 
   // box-shadow: -0 -0 10px 2px #efa63e;
   color: #000000; 
   border-radius: 3px;
   text-align: center; 
    
  }

	.totalbox .title{
      font-size: 10px;
      font-weight: bold;
      margin: 5px;
      text-align: center;
      color: #efa63e;
    }

		.totalbox .total{
      background-color: #FFFFFF;
      font-size: 25px;
      font-weight: bold;
      margin: 5px;
      text-align: center;
    }


    .footer {
      color: #000000;
      padding-bottom: 0px;
      margin-bottom: 0px;
      
    }

	.footer h4{
     padding: 10px;
      padding-top:15px;
      font-size: 8px;
    }




	


</style>';


	$reportHTML = '<htmlpagefooter name="letterfooter2">
  <div class="footer" style="width: 100%; padding-left:45px; padding-bottom:0px;">
    <table width="100%"><tr><td width="30%">
       <img src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/bold360-rgb.png" width="75px"></td><td width="70%" align="right" valign="bottom"><p class="logowording"><span class="smalltext">{PAGENO}</span></p></td></tr>
       
        </table>
      </div>
    </htmlpagefooter>

<htmlpageheader name="letterheader1">
  <div class="footer" style="width: 100%; padding-left:10px; padding-bottom:0px;">
    <table width="100%"><tr><td width="70%"></td><td width="30%">
       <img src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/headerlogo.png" width="275px"></td></tr>
       
        </table>
      </div>
    </htmlpageheader>
       
       <htmlpagefooter name="nofooter">
  <div class="footer" style="width: 100%; padding-left:45px; padding-bottom:0px;">
    
      </div>
    </htmlpagefooter>

  



  <div class="container pdfcontent">
  <!-- Start Page 1 -->
    <div class="row page1">
   <div class="col-xs-11">
     
    
     <h1 class="maintitle">Value Assessment for: ' . $_GET['companyname'] . '</h1>
	
       <h3 class="frontcredits" style="color: white; padding-left: 50px; padding-bottom: 0px; margin-bottom: 0px;">Prepared by: '. $roi_information->get_roi_owner()['first_name'] . ' ' . $roi_information->get_roi_owner()['last_name'] .'</h3>
       <h3 class="frontcredits" style="color: white; padding-left: 50px; padding-bottom: 0px; margin-bottom: 0px;">' . date("F j, Y") . '</h3>
       <h3 class="frontcredits" style="color: white; padding-left: 50px; padding-bottom: 0px; margin-bottom: 0px;"><a style="color:white" href="https://www.theroishop.com/enterprise/2/?roi='.$_GET['roi'].'&v='.$roi_information->get_roi_information()['verification_code'].'">Link to ROI</a></h3>

	</div>
  </div>  
  <!-- End Page 1 -->
       
   <!-- Start Page 2-->    
  <div class="row page2">
   <div class="col-xs-11">
     
    
     <h1 class="bluetitle">Executive Summary</h1>
	<p class="wording">Based on the information provided, with the use of Bold360 over the next 2 years, your organization has the potential to realize a total return on investment of:
</p>
    
    <div class="col-xs-7">
    <h1 class="costamount">'.$_GET['grandtotal'].'</h1>
      
   
    </div>
    <div class="col-xs-4"> 
    <div class="bluerounded">
		<table class="bluetable" width="95%">
    <tr><td colspan="2"><span="strong">ROI Statistics</span></th></tr>
    <tr><td>ROI</td><td class="rightalign">'.$_GET['totalroi'].'</td></tr>
    <tr><td>NPV</td><td class="rightalign">'.$_GET['npv'].'</td></tr>
    <tr><td>Payback Period</td><td class="rightalign">'.$_GET['payback'].' months</td></tr>
    
      
    </table>
</div>  
    </div>
     
<div class="shiftright">
    <br>
     <table id="regtable">
     <tr><th></th><th class="centeralign">Year 1</th><th class="centeralign">Year 2</th><th class="centeralign">Total</th></tr> 
     <tr><th>Cost Management</th><td class="rightalign">'.$_GET['s1'].'</td><td class="rightalign">'.$_GET['s1'].'</td><td class="rightalign">'.$_GET['s2'].'</td></tr> 
     <tr><th>Incremental Revenue</th><td class="rightalign">'.$_GET['s3'].'</td><td class="rightalign">'.$_GET['s3'].'</td><td class="rightalign">'.$_GET['s4'].'</td></tr> 
     <tr><th>Customer Satisfaction</th><td class="rightalign">'.$_GET['s5'].'</td><td class="rightalign">'.$_GET['s5'].'</td><td class="rightalign">'.$_GET['s6'].'</td></tr> 
     <tr><th>Strategic Benefits</th><td class="rightalign">'.$_GET['s7'].'</td><td class="rightalign">'.$_GET['s7'].'</td><td class="rightalign">'.$_GET['s8'].'</td></tr> 
     <tr><th>Total</th><td class="rightalign">'.$_GET['s9'].'</td><td class="rightalign">'.$_GET['s9'].'</td><td class="rightalign">'.$_GET['s10'].'</td></tr> 
     <tr><th>Costs</th><td class="rightalign">'.$_GET['s11'].'</td><td class="rightalign">'.$_GET['s38'].'</td><td class="rightalign">'.$_GET['s39'].'</td></tr> 
     <tr><th>Net Total</th><td class="rightalign">'.$_GET['s13'].'</td><td class="rightalign">'.$_GET['s14'].'</td><td class="rightalign">'.$_GET['s15'].'</td></tr> 
     
     </table>
</div>      
       
	</div>
  </div>   
  <!-- End Page2  -->
 
  <!-- Start Page 3-->    
  <div class="row page3">
   <div class="col-xs-11">
     
    
     <h1 class="bluetitle">Cost Management Savings</h1>
	<p class="wording">Shifting engagements from high-cost channels, like phone and email, to lower cost channels, like live chat and self-service, can drive significant cost savings. We used your existing engagement rates across channels, plus standard industry data, as a benchmark to determine the expected engagement rate for self-service. This will vary based on the type of self-service implemented - dynamic search bar, conversational chatbot, etc.
</p>
    <div class="shiftright">
    <div class="col-xs-7">
    <h1 class="costamount">'.$_GET['s16'].'</h1>
      <div class="bluerounded">
		<table class="bluetable" width="95%">
    <tr><td>Phone Engagement Savings</td><td>'.$_GET['s17'].'</td></tr>
    <tr><td>Email Engagement Savings</td><td>'.$_GET['s18'].'</td></tr>
    <tr><td>Live Chat Savings</td><td>'.$_GET['s19'].'</td></tr>
    <tr><td>Other Messaging <span class="smalltext">(social, text, app-based, etc.)</span></td><td>'.$_GET['s20'].'</td></tr>
      
    </table>
</div>
   
    </div>
    <div class="col-xs-4"> <img src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lgn_page3_logo1.png" width="250px">
      <div class="lightbluebox italic">Just six weeks after implementing Bold360 ai, ICICI bank reported a 55% reduction in agent chats and a 61% reduction in call volume.
</div>  
      <hr>
       <img src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lgn_page3_logo2.png" width="250px">
      <div class="lightbluebox italic">JustFab experienced 17% fewer clicks to the Contact Us page among desktop users, and nearly 66% among mobile users, indicating that customers were self-serving.
</div>  </div>
     
  </div>

	</div>
  </div>  
  <!-- End Page3  -->

    
       <!-- Start Page 4-->    
  <div class="row page3">
   <div class="pagewidth">
     
    
     <h1 class="bluetitle">Incremental Revenue</h1>
	<p class="wording">There is the potential to drive additional revenue from implementing self-service and a conversational chatbot on your website, mobile app, messaging or social channels. Using your existing engagement data, digital conversion rate and average order value, we have calculated the uplift that can come from implementing Bold360 ai.

</p>
    
   <div class="shiftright25"> 
     <div class="col-xs-7 col-xs-offset-2">
       <h1 class="costamount">'.$_GET['s21'].'</h1>
         
      <div class="bluerounded">
		<table class="bluetable" width="95%">
    <tr><td>Current number of digital engagements</td><td>'.$_GET['s22'].'</td></tr>
    <tr><td>Projected number of digital engagements</td><td>'.$_GET['s23'].'</td></tr>
    <tr><td>Increase in annual engagements</td><td>'.$_GET['s24'].'</td></tr>
    <tr><td>Additional revenue from increased engagements</td><td>'.$_GET['s25'].'</td></tr>
    
      
    </table>
</div>
    </div>
    
    </div>
    
    <br><br>
    <div class="col-xs-5">
    <img class="right" style="margin-left: 100px;" src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lgn_page4_logo1.png" width="250px">
    </div>
    <div class="col-xs-6"> 
      <div class="lightbluebox italic">"Bold360 ai  automatically answers more than 13,000 questions per month and frees existing agents to use their time and expertise more efficiently. The solution allows our reps to focus more on the high-value chat requests and the ones who are more likely to convert."

</div>  
      </div>

	</div>
  </div>  
  <!-- End Page4  -->  
  
  <!-- Start Page 5-->    
  <div class="row page3">
   <div class="pagewidth">
     
    
     <h1 class="bluetitle">Customer Satisfaction</h1>
	<p class="wording1">A.I. and self-service can also have an impact on CX metrics like Customer Satisfaction (CSAT) and Net Promotor Score (NPS). By leveraging your specific benchmarks, or through industry benchmarks, we have measured the potential increase in revenue from an improvement in customer satisfaction.


</p>
    
   <div class="shiftright25"> 
     <div class="col-xs-7 col-xs-offset-2">
       <h1 class="costamount1">'.$_GET['s26'].'</h1>
      <div class="bluerounded">
		<table class="bluetable" width="95%">
    <tr><td>Current customer satisfaction score</td><td>'.$_GET['s27'].'</td></tr>
    <tr><td>Projected customer satisfaction score</td><td>'.$_GET['s28'].'</td></tr>
    <tr><td>Estimated financial impact of improvement</td><td>'.$_GET['s29'].'</td></tr> 
    </table>
</div>
    </div>
    </div>

      <div class="shiftright25">
    <table id="comment_table" width="95%">
    <tr>
      <td width="50%" class="rightborder"> <img src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lgn_page5_logo1.png" width="300px"></td>
      <td width="50%"> <img src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lgn_page5_logo2.png" width="300px"></td>
    </tr>
    <tr>
      <td class="rightborder">
        <table id="lightblue"><tr><td><em>"We believe that the key to driving customer satisfaction and repeat purchases is having good self-service processes in place, which Bold360 ai is helping us achieve. It\'s all about making life simpler for your customers."</em></td></tr></table>
          
      </td>
      <td ><table id="lightblue"><tr><td><em>"Bold360 ai extended the reach of the Voice of the Customer for us, whereas before I had to rely only on customer care engagement data. With Bold360 ai I get a clearly communicated story for these customers, whose voices had previously been silent. I can now understand why the flow may be abandoned at a certain time, because Bold360 ai is a sort of "mind reader" for our customers, helping us provide a greater customer experience."</em>
</td></tr></table>  </td></tr>
    
    
      
    </table>
    
	</div>


	</div>
  </div>  
  <!-- End Page 5 -->     
    
     <!-- Start Page 6-->    
  <div class="row page3">
   <div class="pagewidth">
     
    
     <h1 class="bluetitle">Strategic Benefits</h1>
	<p class="wording1">Implementing A.I. can provide additional benefits in both customer facing and agent facing applications. This can include areas such as reallocating agents from customer service to customer acquisition roles or reducing agent onboarding costs.
</p>
    
    <div class="col-xs-7">
    <h1 class="costamount">'.$_GET['s30'].'</h1>
      <p>Importance of reallocating support labor to revenue <br>
        generating activities: <span class="strong">'.$_GET['s31'].'</span></p>
        <p>Financial impact of support reallocation: <span class="strong">'.$_GET['s32'].'</span></p>
      <div class="bluerounded">
		<table class="bluetable" width="95%">
    <tr><td>Annual agents lost to attrition</td><td>'.$_GET['s33'].'</td></tr>
    <tr><td>Cost to onboard a new agent</td><td>'.$_GET['s34'].'</td></tr>
    <tr><td>Current cost of onboarding</td><td>'.$_GET['s35'].'</td></tr>
    <tr><td>Savings by reducing attrition rate by '.$_GET['s36'].'</td><td>'.$_GET['s37'].'</td></tr>
      
    </table>
</div>
   
    </div>
    <div class="col-xs-4"> <img src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lgn_page6_logo1.png" width="200px">
      <div class="lightbluebox1 italic">"Bold360 ai diminished our call center volume by almost half, freeing up our account managers to focus on revenue generating projects as opposed to resolving repetitive issues that were easily solvable with Bold360 ai\'s platform. Implementing Bold360 ai into the most sensitive part of our website - our payments pages - reveals our trust in their services."
</div>  
      <hr>
       <img src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lgn_page6_logo2.png" width="200px">
      <div class="lightbluebox1 italic">"A.I. reduces load on mundane queries, which gives our agents more time to handle higher quality queries. While we may not be able to recruit more people, we are certainly not laying off people due to this - there is a lot of redeployment happening."
</div>  </div>
     
        
	</div>
  </div>  
  <!-- End Page 6 -->  
  
     <!-- Start Page 7-->    
  <div class="row page7">
   <div class="col-xs-11">
     
    
     <img src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/lgn_page7_wording.png" width="1000px">
	
       <p class="finalwording">Bold360 is an intelligent customer engagement solution that provides businesses with actionable customer insights to efficiently deliver richer and more personalized experiences in real time. We provide the digital channels and tools needed to engage and support consumers as they seamlessly move across self-service and agent-assisted channels like chat, email, messaging and social.</p>
       
       <p class="finalwording">Visit <a href="http://www.bold360.com">www.bold360.com</a> to learn more.
</p>
      <p class="finalwording"><span class="disclaimer"><span class="strong">NOTE:</span> The Bold360 ROI calculator produces only estimates and is for informational purposes only. The calculator uses information you supply, or industry averages, and generates an estimate based on several assumptions, including contact volume offset to self-service, projected engagements with self-service, self-service engagement rate, customer value, and deployment strategy. Your actual savings may vary from the calculations and estimates generated. The results produced do not constitute an actual quote, promise, guarantee or contract. For more information, please contact your LogMeIn sales representative.</span></p> 
    <br><br><br>   
 <div class="shiftright">      
<table width="100%"><tr><td width="30%">
       <img src="http://www.theroishop.com/webapps/assets/customwb/10016/img/logmein/headerlogo.png" width="250px"></td><td width="70%" align="right" valign="bottom"><p class="finalwording"><span class="smalltext">© 2018, LogMeIn, Inc. | CONFIDENTIAL
</span></p></td></tr>
       
        </table>
         </div>
	</div>
  </div>  
  <!-- End Page7  -->  
    
  
   

</div>  <!-- end pdf content -->';
	
	$report = '<html><head>' . $reportCSS . '</head><body class="pdfbody">' . $reportHTML . '</body></html>';
	
	$stylesheet = file_get_contents('../assets/css/pdfstyle.css');
	$comp_stylesheet = file_get_contents('../assets/css/style.css');
	
	$mpdf = new mPDF('c', 'A4-L' . $orient);
		
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($comp_stylesheet,1);
			
	$mpdf->WriteHTML($report);
	
	$mpdf->Output( $_GET['companyname'] . '.pdf', 'D' );	

	
?>


