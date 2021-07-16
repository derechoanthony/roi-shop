<?php

	function pdfShell(){
	
		$calculator = new CalculatorActions($db);
		
		$pdfTotalPages = $calculator->retrievePagesForSetup();
		$pdfVerisionId = $calculator->retrieveVersionId();
		
		$pdfBuild = '<div data-show-with="pdf" data-section-holder-id="#pdf" style="display: none;">';
		
		$pdfBuild .= '<div class="col-lg-1">
						<a style="display: none;" id="pdf_create_document" class="btn btn-success btn-sm" style="width: 119px; margin-bottom: 5px;">Download PDF</a><br>
						<a id="pdf_save" class="btn btn-success btn-sm" style="margin-bottom: 5px;">Create PDF</a><br>
						<a style="display: none;" id="pdf_reset" class="btn btn-success btn-sm" style="width: 119px; margin-bottom: 5px;">Reset PDF</a><br>
						<a style="display: none;" id="pdf_output" href="php/pdf_output.php?roi='. $_GET['roi'] .'" class="btn btn-primary btn-sm">PDF Output</a>
						<a style="display: none;" id="pdf_crete_new_template" href="construct/pdf/' . ( $pdfVerisionId[0]['roi_version_id'] == 521 ? 'socialchorus.php' : ( $pdfVerisionId[0]['roi_version_id'] == 601 ? 'cmms.php' : ( $pdfVerisionId[0]['roi_version_id'] == 8 ? 'montage.php' : ( $pdfVerisionId[0]['roi_version_id'] == 609 ? 'rimilia.php' : 'pdfcreator.php' ) ) ) ) . '?roi='. $_GET['roi'] .'" class="btn btn-primary btn-sm">PDF Output</a>
					</div>';
					
		for($i=1; $i<=$pdfTotalPages['MAX(pageno)']; $i++) {
		
			$pdfBuild .= '<div data-page="'.$i.'" style="position: relative; width: 297mm; height:210mm; border: 1px solid #666; margin: auto; background-color: white; margin-bottom: 30px;">';

			foreach($_SESSION['pdfSetup'] as $pdfLine) {
					
				if( $pdfLine['pageno'] == $i ) {
						
					$pdfBuild.= '<div contenteditable="true" style="width: 100%; position: absolute; top:'.($pdfLine['pos_y']).'mm; left:'.($pdfLine['pos_x']).'mm; height:'.($pdfLine['height']).'mm" data-pdf-element="'.$pdfLine['id'].'" data-pos-x="'.$pdfLine['pos_x'].'" data-pos-y="'.$pdfLine['pos_y'].'" data-content-type="'.$pdfLine['content_type'].'">'.$pdfLine['html'].'</div>';
				}
			}

			$pdfBuild.=	'</div>';

		}
		
		$pdfBuild .= '</div>';
		
		return $pdfBuild;
		
	}

?>		