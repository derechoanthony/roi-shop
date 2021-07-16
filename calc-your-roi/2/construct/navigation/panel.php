		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav metismenu" id="side-menu">
					<li class="nav-header">
						<div class="dropdown profile-element">
							<span>
								<img id="company_logo" class="some-button" alt="image" src="company_specific_files/<?= $_SESSION['roiStructure']['company_id'] ?>/logo/logo.png" />
							</span>
						</div>
					</li>
					
<?php

	$firstSection = true;
	
	foreach($sectionCategories as $category) {
		
		$sectionKeys = array_keys(array_column($roiSections, 'roi_section_category'),$category['category_id']);
		
?>
                    <li class="smooth-scroll<?= $firstSection == true ? ' active' : '' ?>">
                        <a href="#">
							<i class="fa fa-th-large"></i>
							<span class="nav-label"><?= $category['category_name'] ?></span>
							<span class="fa arrow">
						</a>
                        <ul class="nav nav-second-level collapse">
<?php
		foreach($sectionKeys as $key) {
			
			$section = $roiSections[$key];
			$sectionExcluded = array_keys(array_column($_SESSION['sectionsExcluded'], 'entity_id'), $section['roi_section_id']);
			
			if(!$sectionExcluded) {
?>
                            <li class="section-navigator">
								<a href="#section<?= $section['roi_section_id'] ?>">
									<?= $section['navigation_title'] ? $section['navigation_title'] : $section['roi_section_title'] ?>
								</a>
							</li>
<?php
			}
		}
?>
                        </ul>
                    </li>
<?php
		
		$firstSection = false;
	}
	
	if($_SESSION['pdfSpecs']) {
?>
					<li id="pdf" class="smooth-scroll active">
						<a href="#"><i class="fa fa-file-pdf-o"></i> <span class="nav-label">Your PDFs</span> <span class="fa arrow"></span></a>
						<ul class="nav nav-second-level collapse">				
							<li>
								<a id="create-pdf" data-section-type="pdf"> View PDF</a>
							</li>
						</ul>
					</li>
<?php
	}
	
	if($_SESSION['verification_lvl'] > 1){
?>
					<li>
						<a href="../../dashboard"><i class="fa fa-globe"></i> <span class="nav-label">My ROIs</span></a>
					</li> 
<?php
	}
?>
				</ul>

            </div>
        </nav>