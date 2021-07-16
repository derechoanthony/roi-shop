<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
class FontModal
{
	
	//Create database object
	private $_db;
		
	/**
	 * Checks for a database object and creates one if none is found
	 **/
	public function __construct($db=NULL)
	{
		if(is_object($db))
		{
			$this->_db = $db;
		}
		else
		{
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
		}
	}
	
	public function createFontModal($modalID, $fonttitle, $fontID, $fontsize, $fontcolor, $fontbold, $fontitalic, $fontunderline){
		
		//Function to pass the field names where the values are stored for this text 
		//And a Modal is returned with these options.
		
		//*****Special Note --> This could also include the table name or element ID to make this more standard for other elements.
		
		//1. Get the values assosicated with the field names
		
		$sql = "SELECT $fontID, $fontsize, $fontcolor, $fontbold, $fontitalic, $fontunderline FROM `tbl_charts_list` t1 
				JOIN `tbl_charts_options_general` t2 
				ON t1.chartID = t2.chartID 
				WHERE t1.chartID = :chartID;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':chartID', $_GET['chartID'] , PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		
		//2. get the Fonts from the reference table
		
		$sql1 = "SELECT * FROM tbl_ref_fonts
				ORDER BY FontName";
		
		$stmt = $this->_db->prepare($sql1);
		$stmt->execute();
		$fonts = $stmt->fetchall();
		
		//3. Set the checked values for yes/no Fields
		
		$boldchecked 		= ( $data[$fontbold] 	  == 1 ? 'checked="checked"' : '' );
		$italicchecked 		= ( $data[$fontitalic] 	  == 1 ? 'checked="checked"' : '' );
		$underlinechecked 	= ( $data[$fontunderline] == 1 ? 'checked="checked"' : '' );
		
		//4. Write the Modal
		$modalhtml =	'';
		$modalhtml = 	'<label class="col-lg-1 fontstyles"><a class="btn btn-white btn-bitbucket" data-toggle="modal" href="#' . $modalID . '"><i class="fa fa-font"></i></a></label>
						<input type="hidden" name="' . $fontID . '" value="' . $data[$fontID] . '" />
						<input type="hidden" name="' . $fontsize . '" value="' . $data[$fontsize] . '" />
						<input type="hidden" name="' . $fontcolor . '" value="' . $data[$fontcolor] . '" />
						<input type="hidden" name="' . $fontbold . '" value="' . $data[$fontbold] . '" />
						<input type="hidden" name="' . $fontitalic . '" value="' . $data[$fontitalic] . '" />
						<input type="hidden" name="' . $fontunderline . '" value="' . $data[$fontunderline] . '" />
						
						<div id="' . $modalID . '" class="modal fade" aria-hidden="true">
						<div class="modal-dialog">
	        			<div class="modal-content">
            			<div class="modal-body">
            			
            			<div class="row">
            			<div class="col-lg-12"><h3 class="m-t-none m-b">' . $fonttitle . ' Font Styles</h3>
            			
                        <br>
                        
                        <div class="form-group">
				            <label class="col-lg-2 control-label smallbold">Font</label>
				            <div class="col-sm-10">
				            <select class="form-control m-b modal-data" name="modal_' . $fontID . '">';
					        foreach( $fonts as $fonts) {
					        	$modalhtml = $modalhtml . '<option value="' . $fonts['fontID'] . '"' .  ($data[$fontID]==$fonts['fontID'] ? 'selected="selected"' : '' ) . '>' . $fonts['FontName'] . '</option>';	
					                                    	}	
					         $modalhtml = $modalhtml . '                           	
				             </select>
				             </div>
				        </div>
                        
						<div class="form-group">
				        	<label class="col-lg-2 control-label smallbold">Size</label>
				            <div class="col-sm-10">
				            <select class="form-control m-b modal-data" name="modal_' . $fontsize . '">';
				            for($i=8; $i<21; $i++){$modalhtml = $modalhtml . '<option value="' . $i .  '"' . ($data[$fontsize] == $i ? ' selected=""': '') .  '>' . $i . ' px</option>';}
							$modalhtml = $modalhtml . '
							</select>
				            </div>
				       </div>
				       
					   <div class="form-group">
					       <label class="col-lg-2 control-label smallbold ">Color</label>
					       <div class="col-lg-10">
					       <input type="text" placeholder="" name="modal_' . $fontcolor . '" value="' . $data[$fontcolor] . '" class="form-control modal-data colorpicker"> 
					       </div>
				       </div>
				       
				       <div class="form-group">
					       <div class="col-lg-12">
				           <div class="i-checks" data-hiddeninput="' . $fontbold . '"><label class="smallbold"> 
				           <input type="checkbox" class="modal-data" name=modal_"' . $fontbold . '" ' . $boldchecked . '><i></i> Bold </label></div>
				           </div>
				       </div>
				       
				       <div class="form-group">
					       <div class="col-lg-12">
				           <div class="i-checks" data-hiddeninput="' . $fontitalic . '"><label class="smallbold"> 
				           <input type="checkbox" class="modal-data" name="modal_' . $fontitalic . '" ' . $italicchecked . '><i></i> Italic </label></div>
				           </div>
				       </div>
				       
					   <div class="form-group">
					       <div class="col-lg-12">
				           <div class="i-checks" data-hiddeninput="' . $fontunderline . '"><label class="smallbold"> 
				           <input type="checkbox" class="modal-data" name="modal_' . $fontunderline . '" ' . $underlinechecked . '><i></i> Underline </label></div>
				           </div>
				       </div>
				       
				                              
					   <button type="button" class="btn btn-primary pull-left SaveChartOpts" data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span> DONE</button>
					   
					   
				       
						
						
            			</div></div></div></div></div></div>';
		
		
		//5. Return the Modal
		
		return $modalhtml;
		
	}
	
	
	
}	




