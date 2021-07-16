<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

	/******************************************
		Load all require files on page load
	 ******************************************/
	
	require_once("../../db/constants.php");
	require_once("../../db/connection.php");
	//require_once( "../../../common/phpfunctions-RUDD.php" ); 					
	
	function hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $rgb; // returns an array with the rgb values
	}
	
	//Get the options for this chart
	$ROICalcElemID = $_GET["ROICalcElemID"];

		$sql = "SELECT * FROM `tbl_calc_elements` t1
				JOIN `tbl_charts_list` t2
				ON  t1.ROICalcElemID=t2.chartID
				JOIN `tbl_charts_options_general` t3
				ON t2.chartID=t3.chartID,
				(SELECT t6.Fontcss AS subtitlefontcss FROM tbl_ref_fonts t6 WHERE t6.fontID=(SELECT A.subtitlefontID FROM tbl_charts_options_general A JOIN tbl_charts_list B ON A.chartID=B.chartID WHERE B.ROICalcElemID= :ElemID )) AS AA,
				(SELECT t6.Fontcss AS titlefontcss FROM tbl_ref_fonts t6 WHERE t6.fontID=(SELECT A.titlefontID FROM tbl_charts_options_general A JOIN tbl_charts_list B ON A.chartID=B.chartID WHERE B.ROICalcElemID= :ElemID )) AS AB,
				(SELECT t6.Fontcss AS 	xAxisTitleFontcss FROM tbl_ref_fonts t6 WHERE t6.fontID=(SELECT A.xAxisTitleFontID FROM tbl_charts_options_general A JOIN tbl_charts_list B ON A.chartID=B.chartID WHERE B.ROICalcElemID= :ElemID )) AS AC,
				(SELECT t6.Fontcss AS 	xAxisLabelsFontcss FROM tbl_ref_fonts t6 WHERE t6.fontID=(SELECT A.xAxisLabelsFontID FROM tbl_charts_options_general A JOIN tbl_charts_list B ON A.chartID=B.chartID WHERE B.ROICalcElemID= :ElemID )) AS AD,
				(SELECT t6.Fontcss AS 	legendItemFontcss FROM tbl_ref_fonts t6 WHERE t6.fontID=(SELECT A.legendItemFontID FROM tbl_charts_options_general A JOIN tbl_charts_list B ON A.chartID=B.chartID WHERE B.ROICalcElemID= :ElemID )) AS AE,
				(SELECT t6.Fontcss AS 	legendTitleFontcss FROM tbl_ref_fonts t6 WHERE t6.fontID=(SELECT A.legendTitleFontID FROM tbl_charts_options_general A JOIN tbl_charts_list B ON A.chartID=B.chartID WHERE B.ROICalcElemID= :ElemID )) AS AF
				WHERE t1.ROICalcElemID= :ElemID;";
		//*/
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':ElemID', $ROICalcElemID, PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
	
	//print_r($data);
	
	//Get the options for each series associated with this chart
		$sql = "SELECT * ,				
				(SELECT t2.Fontcss FROM tbl_ref_fonts t2 WHERE t2.fontID=t1.datalabelFontID) AS datalabelfontcss
				FROM `tbl_charts_options_series` t1
				WHERE t1.chartID= :chartID
				ORDER BY t1.position;";
		//*/
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':chartID', $data['chartID'], PDO::PARAM_INT);
		$stmt->execute();
		$series = $stmt->fetchall();
	
		$chartopts_series = array();
		$seriescount = 0;
		foreach($series as $i){
			
			$sectionExcluded = array_keys(array_column($_SESSION['sectionsExcluded'], 'entity_id'), $i['show_with_section']);
			if(!$sectionExcluded) {		
			
				$chartopts_gradient_fill = array(
											"radialGradient" 	=> array(
																	"cx"	=> 0.5,
																	"cy"	=> 0.3,
																	"r"		=> 0.7
																),
											"stops"				=> array(
																	array(
																		0, $i['gradientStop1']
																	),
																	array(
																		1, $i['gradientStop2']
																	)
																)
										);
											
				$chartopts_color = ( $i['gradientFill'] ? $chartopts_gradient_fill : $i['seriesAreaColor'] );
				
				$chartopts_series[$seriescount] = array(
													"type" 			=> $i['seriesType'],
													"color" 		=> $chartopts_color,
													"data"			=> array(0,0,0,0),
													"fillOpacity" 	=> $i['seriesAreaTrans'] / 100,
													"marker" 		=> array(
																		"symbol" 	=> $i['seriesPointSymbol'],
																		"lineColor" => $i['seriesLineColor'],
																		"lineWidth" => $i['seriesPointSize'],
																		"fillColor" => $i['seriesPointColor']
																		),
													"linecolor" 	=> $i['seriesLineColor'],
													"dashStyle" 	=> $i['seriesLineStyle'],
													"name" 			=> $i['seriesTitle'],
													"yAxis"			=> intval( $i['yAxisGrid'] ),
													"tooltip"		=> array(
																		"pointFormat"	=> $i['datalabelValue'],
																		"valueSuffix"	=> $i['datalabelSuffix'],
																		"valuePrefix"	=> $i['datalabelPrefix']
																		),
													"zIndex"		=>	$i['position'] * 1,
													"dataLabels"	=> array(
																		"enabled"		=> ( $i['datalabelEnabled']  == 0 ? false : true ),
																		"style"			=> array(
																							"font-family"	=> $i['datalabelfontcss'],
																							"fontSize" 		=> $i['datalabelFontSize'] . 'px',
																							"color"			=> $i['datalabelFontColor']
																						),
																		"format" 		=> $i['datalabelPrefix'] . $i['datalabelValue'] . $i['datalabelSuffix']
																	)
												);
												
				$seriescount = $seriescount + 1;
			}
		}
	
	$dimensional_options = array(
							"enabled" 			=>	( $data['3DEnabled'] == 0 ? 'false' : 'true' ),
							"alpha"				=>	$data['3Dalpha'],
							"beta"				=>	$data['3Dbeta'],
							"viewDistance"		=>	$data['3DviewDistance'],
							"depth"				=>	$data['3Ddepth']
						);
						
	$chartopts_chart = array(
							"renderTo" 			=> 'ROICalcElemID' . $ROICalcElemID,
							"type" 				=> $data['chartType'],
							"height"			=> ( $data['chartHeight'] ? $data['chartHeight'] : '400' ),
							"options3d"			=> $dimensional_options,
							"backgroundColor" 	=> $data['backColor'],
							"borderColor" 		=> $data['borderColor'],
							"borderWidth" 		=> $data['borderThickness'],
							"borderRadius"		=> $data['borderRadius'],
							"zoomType" 			=> "xy"
						);
					
	$chartopts_title = array(
							"text" 		=> $data['titleText'],
							"style" 	=> array(
											"font-family"		=> $data['titlefontcss'],
											"color" 			=> $data['titleColor'],
											"fontSize" 			=> $data['titlefontsize'] . 'px',
											"fontWeight" 		=> ( $data['titleBold']  == 0 ? '' : 'bold' ),
											"font-style" 		=> ( $data['titleItalic']  == 0 ? 'normal' : 'italic' ),
											"text-decoration" 	=> ( $data['titleUnderline']  == 0 ? 'none' : 'underline' )
										)
						);
	
	$sql = "SELECT * FROM tbl_charts_xaxis_categories
			WHERE chart_id = :chartid
			ORDER BY position;";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':chartid', $ROICalcElemID, PDO::PARAM_INT);
	$stmt->execute();
	$xAxisCategories = $stmt->fetchall();
	
	$chartopts_category_names = [];
	
	foreach($xAxisCategories as $xAxisCategory){
		$chartopts_category_names[] = $xAxisCategory['category_name'];
	}
									
	$chartopts_xAxis = array(
							"categories"	=> $chartopts_category_names,
							"title" 		=> array(
												"text" 	=> $data['xAxisTitleText'],
												"style" => array(
															"font-family"		=>$data['xAxisTitleFontcss'],
															"color" 			=> $data['xAxisTitlecolor'],
															"fontSize" 			=> $data['xAxisTitleFontSize'] . 'px',
															"fontWeight" 		=> ( $data['xAxisTitleBold']  == 0 ? '' : 'bold' ),
															"font-style"		=> ( $data['xAxisTitleItalic']  == 0 ? 'normal' : 'italic' ),
															"text-decoration"	=> ( $data['xAxisTitleUnderline']  == 0 ? 'none' : 'underline' )
														)
							),
							"labels" 		=> array(
												"enabled" 	=> ( $data['xAxisLabelsEnabled']  == 0 ? false : true ),
												"rotation" 	=> $data['xAxisLabelsRotation'],
												"style" 	=> array(
																"font-family"		=> $data['xAxisLabelsFontcss'],
																"color"				=> $data['xAxisLabelsColor'],
																"fontSize" 			=> $data['xAxisLabelsFontSize'] . 'px',
																"fontWeight" 		=> ( $data['xAxisLabelsBold']  == 0 ? '' : 'bold' ),
																"font-style" 		=> ( $data['xAxisLabelsItalic']  == 0 ? 'normal' : 'italic' ),
																"text-decoration" 	=> ( $data['xAxisLabelsUnderline']  == 0 ? 'none' : 'underline' )
															)
							),
							"tickWidth" 	=> $data['xAxisLabelsEnabled']
						);
						
	$sql = "SELECT * FROM tbl_charts_yaxis_options
			WHERE chart_id = :chartid
			ORDER BY position;";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':chartid', $ROICalcElemID, PDO::PARAM_INT);
	$stmt->execute();
	$yAxisLegends = $stmt->fetchall();
	
	$chartopts_yaxis_legends = [];
	
	foreach($yAxisLegends as $yAxisLegend){
		$chartopts_yaxis_legends[] = array(
										"min" 		=> intval($yAxisLegend['min']),
										"labels" 	=> array(
														"format" 	=> $yAxisLegend['label_format'],
														"align"		=> ( $yAxisLegend['label_align']  == 0 ? 'right' : 'left' ),
														"x"			=> $yAxisLegend['label_x']/1,
														"style" 	=> array(
																		"color" 	=> $yAxisLegend['label_color']
																		)
														),
										"title" 	=> array(
														"style" 	=> array(
														"color" 	=> $yAxisLegend['label_color']
														),
										"text" 		=> ( $yAxisLegend['label_title'] ? $yAxisLegend['label_title'] : '' )
										),
										"opposite" 	=> ( $yAxisLegend['opposite'] ? true : false )
									);
	}
	
	$chartopts_legend = array(
							"enabled" 			=> ( $data['LegendEnabled'] == 0 ? false : true ),
							"layout" 			=> $data['legendLayout'],
							"shadow"			=> ( $data['legendShadow'] == 0 ? false : true ),
							"align"		 		=> $data['legendAlign'],
							"verticalAlign"		=> $data['legendVerticalAlign'],
							"floating"			=> ( $data['legendFloating'] == 0 ? false : true ),
							"x" 				=> intval($data['legendx']),
							"y" 				=> intval($data['legendy']),
							"backgroundColor" 	=> $data['legendBackColor'],
							"borderColor" 		=> $data['legendBorderColor'],
							"borderRadius" 		=> $data['legendBorderRadius'],
							"borderWidth" 		=> $data['legendBorderThickness'],
							"title" 			=> array(
													"text" 			=> $data['legendTitle'],
													"style" 		=> array(
																		"font-family" 		=> $data['legendTitleFontcss'],
																		"color" 			=> $data['legendTitleFontColor'],
																		"fontSize" 			=> $data['legendTitleFontSize'] . 'px',
																		"fontWeight" 		=> ( $data['legendTitleBold']  == 0 ? '' : 'bold' ),
																		"font-style" 		=> ( $data['legendTitleItalic']  == 0 ? 'normal' : 'italic' ),
																		"text-decoration" 	=> ( $data['legendTitleUnderline']  == 0 ? 'none' : 'underline' )
																	)
													),
							"itemStyle" 		=> array(
													"font-family"		=> $data['legendItemFontcss'],
													"color"				=> $data['legendItemColor'],
													"fontSize" 			=> $data['legendItemFontSize'] . 'px',
													"fontWeight" 		=> ( $data['legendItemBold']  == 0 ? '' : 'bold' ),
													"font-style" 		=> ( $data['legendItemItalic']  == 0 ? 'normal' : 'italic' ),
													"text-decoration" 	=> ( $data['legendItemUnderline']  == 0 ? 'none' : 'underline' )
													)
						);

	$chartopts_plotOptions = array(
								"series" 		=> array(
													"animation" => true
												),
								"column" 		=> array( 
													"stacking" 			=> ( $data['columnStacking'] ? 'normal' : '' ),
													"pointPadding"		=> $data['pointPadding']
												),
								"pie"			=> array(
													"allowPointSelect"	=> true,
													"slicedOffset"		=> '30',
													"cursor"			=> 'pointer',
													"depth"				=> '35',
													"showInLegend"		=> true,
													"dataLabels"		=> array(
																			"enabled"			=>	true,
																			"borderRadius" 		=>	'1',
																			"backgroundColor"	=>	'rgba(252,255,197,0.7)',
																			"color"				=>	'black',
																			"borderWidth"		=>	'1',
																			"borderColor"		=>	'#AAA',
																			"distance"			=>	'-15'
													)								
												)
							);

	$chartopts_subtitle = array(
							"text" 	=> $data['subtitleText'],
							"style"	=> array(
										"font-family"=>$data['subtitlefontcss'],
										"color" => $data['subtitleColor'],
										"fontSize" => $data['subtitlefontsize'] . 'px',
										"fontWeight" => ( $data['subtitleBold']  == 0 ? '' : 'bold' ),
										"font-style" => ( $data['subtitleItalic']  == 0 ? 'normal' : 'italic' ),
										"text-decoration" => ( $data['subtitleUnderline']  == 0 ? 'none' : 'underline' )
									)
						);						
	
	$chartopts_credits = array(
							"enabled" => ( $data['creditsEnabled']  == 0 ? false : true )
						);
	
	$chartopts_export = array(
							"enabled" => ( $data['exportEnabled']  == 0 ? false : true )
						);
						
	$chartopts_tooltip = array(
							"shared"		=>	"true"
							);
							
	$result = array(
		"chart" 		=> $chartopts_chart,
		"title" 		=> $chartopts_title,
		"xAxis" 		=> $chartopts_xAxis,
		"yAxis"			=> $chartopts_yaxis_legends,
		"legend"		=> $chartopts_legend,
		"plotOptions" 	=> $chartopts_plotOptions,
		"subtitle" 		=> $chartopts_subtitle,
		"credits" 		=> $chartopts_credits,
		"series" 		=> $chartopts_series,
		"exporting" 	=> $chartopts_export,
		"tooltip"		=> $chartopts_tooltip
	);

	//print json_encode($result, JSON_NUMERIC_CHECK);
	print json_encode($result);
	
?>