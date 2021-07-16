<div data-show-with="pdf" data-section-holder-id="#pdf" style="display: none;">
	<div class="col-lg-1">
		<a style="display: none;" id="pdf_create_document" class="btn btn-success btn-sm" style="width: 119px; margin-bottom: 5px;">Download PDF</a><br>
		<a id="pdf_save" class="btn btn-success btn-sm" style="margin-bottom: 5px;">Create PDF</a><br>
		<a style="display: none;" id="pdf_reset" class="btn btn-success btn-sm" style="width: 119px; margin-bottom: 5px;">Reset PDF</a><br>
		<a style="display: none;" id="pdf_output" href="php/pdf_output.php?roi=<?= $_GET['roi'] ?>" class="btn btn-primary btn-sm">PDF Output</a>
		<a style="display: none;" id="pdf_create_new_template" href="construct/pdf/pdfgenerators/pdfcreator.php?roi=<?= $_GET['roi'] ?>" class="btn btn-primary btn-sm">PDF Output</a>
	</div>
<?php
	
	for($i=1; $i<=$_SESSION['pdfMaxPages']['MAX(pageno)']; $i++) {
?>
		<div data-page="<?= $i ?>"
			style="position: relative; width: 297mm; height:210mm; border: 1px solid #666; margin: auto; background-color: white; margin-bottom: 30px;">

<?php			
			$pdfPageKeys = array_keys(array_column($_SESSION['pdfSpecs'], 'pageno'), $i );
			
			foreach($pdfPageKeys as $key) {
?>						
					<div contenteditable="true" style="width: 100%; position: absolute; top:<?= $_SESSION['pdfSpecs'][$key]['pos_y'] ?>mm; left:<?= $_SESSION['pdfSpecs'][$key]['pos_x'] ?>mm; height:<?= $_SESSION['pdfSpecs'][$key]['height'] ?>mm" data-pdf-element="<?= $_SESSION['pdfSpecs'][$key]['pdf_baseline_spec_id'] ?>" data-pos-x="<?= $_SESSION['pdfSpecs'][$key]['pos_x'] ?>" data-pos-y="<?= $_SESSION['pdfSpecs'][$key]['pos_y'] ?>" data-content-type="<?= $_SESSION['pdfSpecs'][$key]['content_type'] ?>"><?= str_replace('%roiid%',$_GET['roi'],$_SESSION['pdfSpecs'][$key]['html']) ?></div>
<?php
			}
?>
			
		</div>
<?php
	
	}
?>
</div>