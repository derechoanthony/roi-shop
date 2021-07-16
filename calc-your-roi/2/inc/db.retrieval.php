<?php

	$_SESSION['verification_lvl'] = $verification_lvl;
	
	$sql = "SELECT * FROM comp_specs
			WHERE compID = (	
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi
			)
			LIMIT 1;";
	
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$calculatorSpecs = $stmt->fetch();
	$_SESSION['calculatorSpecs'] = $calculatorSpecs;
	
	$sql = "SELECT * FROM roi_structure
			WHERE roi_structure_id = (	
				SELECT roi_structure_id FROM roi_structure_version
				WHERE structure_version_id = (
					SELECT roi_version_id FROM ep_created_rois WHERE
					roi_id = :roi
				)
			)
			LIMIT 1;";
	
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['roiMasterStructure'] = $stmt->fetch();
	
	$sql = "SELECT * FROM roi_structure_version
			WHERE structure_version_id = (	
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi
			)
			LIMIT 1;";
	
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['roiStructure'] = $stmt->fetch();
	
	$sql = "SELECT * FROM roi_section_categories
			WHERE structure_version_id = (	
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi
			) AND active = 1
			ORDER BY position;";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$sectionCategories = $stmt->fetchall();
	
	$sql = "SELECT * FROM roi_sections
			WHERE structure_version_id = (
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi
			) AND active = 1
			ORDER BY position;";
					
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$roiSections = $stmt->fetchall();
	
	$sql = "SELECT * FROM calculation_elements
			WHERE structure_id = (
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi
			) AND active = 1;";
				
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['allRoiInputs'] = $stmt->fetchall();
	
	$sql = "SELECT value, entryid FROM roi_values
			WHERE roiid = :roi
			ORDER BY dt DESC";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['inputValues'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM videos
			WHERE video_id IN (
				SELECT reference_id FROM roi_section_elements
				WHERE element_type = 2 AND roi_section_id IN (
					SELECT roi_section_id FROM roi_sections
					WHERE structure_version_id = (
						SELECT roi_version_id FROM ep_created_rois WHERE
						roi_id = :roi					
					)
				)
			)";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['roiVideos'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM toggle_options
			WHERE element_id IN (
				SELECT reference_id FROM roi_section_elements
				WHERE element_type = 3 AND roi_section_id IN (
					SELECT roi_section_id FROM roi_sections
					WHERE structure_version_id = (
						SELECT roi_version_id FROM ep_created_rois WHERE
						roi_id = :roi					
					)
				)
			)";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['toggleOptions'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM registered_functions
			WHERE structure_id = (
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi
			);";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['allEquations'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM tab_tabs
			WHERE structure_id = (
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi
			);";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['allTabs'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM table_properties
			WHERE structure_id = (
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi
			);";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['allTables'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM user_output_value
			WHERE roiid = :roi;";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['overriddenValues'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM custom_table_rows
			WHERE roi_id =:roi
			ORDER BY position;";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['customTableRows'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM custom_table_cells
			WHERE row_id IN (
				SELECT row_id FROM custom_table_rows
				WHERE roi_id = :roi
			)
			ORDER BY position;";
				
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['customTableCells'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM custom_calculation_elements
			WHERE custom_calculation_element_id IN (
				SELECT reference_id FROM custom_table_cells
				WHERE row_id IN (
					SELECT row_id FROM custom_table_rows
					WHERE roi_id = :roi
				)
			);";
				
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['customRoiInputs'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM table_rows
			WHERE table_id IN (
				SELECT table_id FROM table_properties
				WHERE structure_id = (
					SELECT roi_version_id FROM ep_created_rois WHERE
					roi_id = :roi
				)			
			)
			ORDER BY position;";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['tableRows'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM table_cells
			WHERE row_id IN (
				SELECT row_id FROM table_rows
				WHERE table_id IN (
					SELECT table_id FROM table_properties
					WHERE structure_id = (
						SELECT roi_version_id FROM ep_created_rois WHERE
						roi_id = :roi
					)			
				)			
			)
			ORDER BY position;";
				
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['tableCells'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM text_elements
			WHERE text_element_id IN (
				SELECT reference_id FROM roi_section_elements
				WHERE element_type = 5 AND roi_section_id IN (
					SELECT roi_section_id FROM roi_sections
					WHERE structure_version_id = (
						SELECT roi_version_id FROM ep_created_rois WHERE
						roi_id = :roi					
					)
				)
			) OR text_element_id IN (
				SELECT reference_id FROM tab_elements
				WHERE element_type = 5 AND tab_id IN (
					SELECT tab_id FROM tab_tabs
					WHERE structure_id = (
						SELECT roi_version_id FROM ep_created_rois WHERE
						roi_id = :roi				
					)
				)			
			)";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['textElements'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM roi_section_elements
			WHERE roi_section_id IN (
				SELECT roi_section_id FROM roi_sections
				WHERE structure_version_id = (
					SELECT roi_version_id FROM ep_created_rois WHERE
					roi_id = :roi				
				)
			)
			ORDER BY position;";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['roiSectionElements'] = $stmt->fetchall();

	$sql = "SELECT * FROM slider_options
			WHERE calculation_element_id IN (
				SELECT calculation_element_id FROM calculation_elements
				WHERE type = 11 AND structure_id = (
					SELECT roi_version_id FROM ep_created_rois WHERE
					roi_id = :roi				
				)	
			);";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['sliderElements'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM tab_elements
			WHERE tab_id IN(
				SELECT tab_id FROM tab_tabs
				WHERE structure_id = (
					SELECT roi_version_id FROM ep_created_rois WHERE
					roi_id = :roi			
				)					
			)
			ORDER BY position;";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['tabElements'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM dropdown_choices
			WHERE calculation_element_id IN (
				SELECT calculation_element_id FROM calculation_elements
				WHERE type = 3 AND structure_id = (
					SELECT roi_version_id FROM ep_created_rois WHERE
					roi_id = :roi			
				)
			);";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['dropdownChoices'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM ep_created_rois
			WHERE roi_id = :roi
			LIMIT 1;";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$roiSpecifics = $stmt->fetch();
	
	$sql = "SELECT * FROM tbl_charts_options_series
			WHERE chartID IN (
				SELECT reference_id FROM roi_section_elements
				WHERE element_type = 8 AND roi_section_id IN (
					SELECT roi_section_id FROM roi_sections
					WHERE structure_version_id = (
						SELECT roi_version_id FROM ep_created_rois WHERE
						roi_id = :roi					
					)
				)
			) ORDER BY position;";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['chartSeries'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM tbl_series_data
			WHERE series_id IN (
				SELECT seriesID FROM tbl_charts_options_series
				WHERE chartID IN (
					SELECT reference_id FROM roi_section_elements
					WHERE element_type = 8 AND roi_section_id IN (
						SELECT roi_section_id FROM roi_sections
						WHERE structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois WHERE
							roi_id = :roi					
						)
					)
				)
			)
			ORDER BY position";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['chartSeriesData'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM pdf_baseline_specs
			WHERE roi = (
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi	
			);";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['pdfSpecs'] = $stmt->fetchall();
	
	$sql = "SELECT MAX(pageno) FROM pdf_baseline_specs
			WHERE roi = (
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi
			);";
				
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['pdfMaxPages'] = $stmt->fetch();
	
	$sql = "SELECT * FROM roi_users
			WHERE user_id = (
				SELECT user_id FROM ep_created_rois
				WHERE roi_id = :roi
			)";
				
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$calculatorOwner = $stmt->fetch();
	
	$sql = "SELECT * FROM testimonial_holder
			WHERE structure_version_id = (
				SELECT roi_version_id FROM ep_created_rois WHERE
				roi_id = :roi
			)";
			
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['testimonialHolders'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM testimonial_blockquotes
			WHERE testimonial_holder_id IN ( 
				SELECT testimonial_holder_id FROM testimonial_holder
				WHERE structure_version_id = (
					SELECT roi_version_id FROM ep_created_rois WHERE
					roi_id = :roi				
				)			
			)
			ORDER BY position;";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['testimonialBlockquotes'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM hidden_entities
			WHERE type = 'section' AND roi = :roi;";
				
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['sectionsExcluded'] = $stmt->fetchall();
	
	$sql = "SELECT * FROM exchange_rates
			ORDER BY full_name";
						
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$_SESSION['availableCurrencies'] = $stmt->fetchall();
	
	$sql = "SELECT roi_currency.currency, exchange_rates.rate, exchange_rates.dt, exchange_rates.full_name FROM roi_currency 
			JOIN exchange_rates
			ON roi_currency.currency = exchange_rates.currency
			WHERE roiid = :roi;";
				
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$_SESSION['roiCurrency'] = $stmt->fetch();
	
?>