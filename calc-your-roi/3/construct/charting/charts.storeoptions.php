<?php

	require_once("../../db/constants.php");
	require_once("../../db/connection.php");
		

	
	if( isset($_POST['action']) ) {	
	
	//Added for chart Options
	
	
	
	if( $_POST['action'] == 'storeChartOpts' ) {
			
				
				$sql = "DELETE 
						FROM tbl_charts_options_series
						WHERE chartID=:chartID;";
				
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':chartID', $_POST['chartID'], PDO::PARAM_INT);
				$stmt->execute();
			
				$numseries = $_POST['form_series_number'];
				echo $numseries;
				for ($i = 1; $i<=$numseries; $i++){
					
				$sql = "INSERT INTO tbl_charts_options_series
						(chartID, seriesType, seriesTitle, seriesAreaColor, 
						seriesAreaTrans, seriesPointColor, seriesPointSymbol, 
						seriesPointSize, seriesLineColor, seriesLineStyle,
						datalabelEnabled,
						datalabelPrefix,datalabelValue,datalabelSuffix,
						datalabelFontID,datalabelFontSize,datalabelFontColor)
						VALUES
						(:chartID,:seriesType,:seriesTitle,:seriesAreaColor, 
						:seriesAreaTrans, :seriesPointColor, :seriesPointSymbol, 
						:seriesPointSize, :seriesLineColor , :seriesLineStyle,
						:datalabelEnabled,
						:datalabelPrefix,:datalabelValue,:datalabelSuffix,
						:datalabelFontID,:datalabelFontSize,:datalabelFontColor);";
				
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':chartID', $_POST['chartID'], PDO::PARAM_INT);
				$stmt->bindParam(':seriesType', $_POST[$i . '-seriestype'], PDO::PARAM_STR);
				$stmt->bindParam(':seriesTitle', $_POST[$i . '-seriestitle'], PDO::PARAM_STR);
				$stmt->bindParam(':seriesAreaColor', $_POST[$i . '-seriesareacolor'], PDO::PARAM_STR);
				$stmt->bindParam(':seriesAreaTrans', $_POST[$i . '-seriesareatrans'], PDO::PARAM_INT);
				$stmt->bindParam(':seriesPointColor', $_POST[$i . '-seriespointcolor'], PDO::PARAM_STR);
				$stmt->bindParam(':seriesPointSymbol', $_POST[$i . '-seriespointsymbol'], PDO::PARAM_STR);
				$stmt->bindParam(':seriesPointSize', $_POST[$i . '-seriespointsize'], PDO::PARAM_STR);
				$stmt->bindParam(':seriesLineColor', $_POST[$i . '-serieslinecolor'], PDO::PARAM_STR);
				$stmt->bindParam(':seriesLineStyle', $_POST[$i . '-serieslinestyle'], PDO::PARAM_STR);
				$stmt->bindParam(':datalabelEnabled', $_POST[$i . '-datalabelEnabled'], PDO::PARAM_INT);
				$stmt->bindParam(':datalabelPrefix', $_POST[$i . '-datalabelPrefix'], PDO::PARAM_STR);
				$stmt->bindParam(':datalabelValue', $_POST[$i . '-datalabelValue'], PDO::PARAM_STR);
				$stmt->bindParam(':datalabelSuffix', $_POST[$i . '-datalabelSuffix'], PDO::PARAM_STR);
				$stmt->bindParam(':datalabelFontID', $_POST[$i . '-datalabelFontID'], PDO::PARAM_INT);
				$stmt->bindParam(':datalabelFontSize', $_POST[$i . '-datalabelFontSize'], PDO::PARAM_INT);
				$stmt->bindParam(':datalabelFontColor', $_POST[$i . '-datalabelFontColor'], PDO::PARAM_STR);
				$stmt->execute();
					
					
				}
			
			
				$sql = "UPDATE tbl_charts_list 
						SET chartName	=:chartName,
							compID		=:compID,
							chartType	= 0
						WHERE chartID=:chartID;";
				
				//echo $sql;
								
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':chartID', $_POST['chartID'], PDO::PARAM_INT);
				$stmt->bindParam(':chartName', $_POST['chartName'], PDO::PARAM_STR);
				$stmt->bindParam(':compID', $_POST['compID'], PDO::PARAM_INT);
				$stmt->execute();
				
				$sql = "UPDATE tbl_charts_options_general 
						SET titleText			=:titleText,
							titlefontID			=:titlefontID,
							titleColor			=:titleColor,
							titlefontsize		=:titlefontsize,
							titleBold			=:titleBold,
							titleItalic			=:titleItalic,
							titleUnderline		=:titleUnderline,
							subtitleText		=:subtitleText,
							subtitlefontID		=:subtitlefontID,
							subtitleColor		=:subtitleColor,
							subtitlefontsize	=:subtitlefontsize,
							subtitleBold		=:subtitleBold,
							subtitleItalic		=:subtitleItalic,
							subtitleUnderline	=:subtitleUnderline,
							backColor			=:backColor,
							borderColor			=:borderColor,
							borderThickness		=:borderThickness,
							borderRadius		=:borderRadius,
							xAxisTitleText		=:xAxisTitleText,
							xAxisTitleFontID	=:xAxisTitleFontID,
							xAxisTitlecolor		=:xAxisTitleColor,
							xAxisTitleFontSize	=:xAxisTitleFontSize,
							xAxisTitleBold		=:xAxisTitleBold,
							xAxisTitleItalic	=:xAxisTitleItalic,
							xAxisTitleUnderline	=:xAxisTitleUnderline,
							xAxisLabelsEnabled	=:xAxisLabelsEnabled,
							xAxisLabelsRotation	=:xAxisLabelsRotation,
							xAxisLabelsFontID	=:xAxisLabelsFontID,
							xAxisLabelsColor	=:xAxisLabelsColor,
							xAxisLabelsFontSize	=:xAxisLabelsFontSize,
							xAxisLabelsBold		=:xAxisLabelsBold,
							xAxisLabelsItalic	=:xAxisLabelsItalic,
							xAxisLabelsUnderline=:xAxisLabelsUnderline,
							LegendEnabled		=:LegendEnabled,
							legendItemFontID	=:legendItemFontID,
							legendItemFontSize	=:legendItemFontSize,
							legendItemColor		=:legendItemColor,
							legendItemBold		=:legendItemBold,
							legendItemItalic	=:legendItemItalic,
							legendItemUnderline	=:legendItemUnderline,
							legendBackColor		=:legendBackColor,
							legendBorderColor	=:legendBorderColor,
							legendBorderThickness		=:legendBorderThickness,
							legendBorderRadius	=:legendBorderRadius,
							legendAlign			=:legendAlign,
							legendVerticalAlign	=:legendVerticalAlign,
							legendx				=:legendx,
							legendy				=:legendy,
							legendLayout		=:legendLayout,
							legendShadow		=:legendShadow,
							legendShadowColor	=:legendShadowColor,
							legendTitle			=:legendTitle,
							legendTitleFontID	=:legendTitleFontID,
							legendTitleFontSize	=:legendTitleFontSize,
							legendTitleFontColor=:legendTitleFontColor,
							legendTitleBold		=:legendTitleBold,
							legendTitleItalic	=:legendTitleItalic,
							legendTitleUnderline=:legendTitleUnderline,
							legendPosition		=:legendPosition
							
						WHERE chartID=:chartID;";
				
				//print $sql;
								
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':chartID', $_POST['chartID'], PDO::PARAM_INT);
				$stmt->bindParam(':titleText', $_POST['titletext'], PDO::PARAM_STR);
				$stmt->bindParam(':titlefontID', $_POST['titlefontID'], PDO::PARAM_INT);
				$stmt->bindParam(':titleColor', $_POST['titleColor'], PDO::PARAM_STR);
				$stmt->bindParam(':titlefontsize', $_POST['titlefontsize'], PDO::PARAM_INT);
				//$titleBold = (isset($_POST['titleBold']) ? 1 : 0);
				$stmt->bindParam(':titleBold', $_POST['titleBold'], PDO::PARAM_INT);
				//$titleItalic = (isset($_POST['titleItalic']) ? 1 : 0);
				$stmt->bindParam(':titleItalic', $_POST['titleItalic'], PDO::PARAM_INT);
				//$titleUnderline = (isset($_POST['titleUnderline']) ? 1 : 0);
				$stmt->bindParam(':titleUnderline', $_POST['titleUnderline'], PDO::PARAM_INT);
				$stmt->bindParam(':subtitleText', $_POST['subtitletext'], PDO::PARAM_STR);
				$stmt->bindParam(':subtitlefontID', $_POST['subtitlefontID'], PDO::PARAM_INT);
				$stmt->bindParam(':subtitleColor', $_POST['subtitleColor'], PDO::PARAM_STR);
				$stmt->bindParam(':subtitlefontsize', $_POST['subtitlefontsize'], PDO::PARAM_INT);
				//$subtitleBold = (isset($_POST['subtitleBold']) ? 1 : 0);
				$stmt->bindParam(':subtitleBold', $_POST['subtitleBold'], PDO::PARAM_INT);
				//$subtitleItalic = (isset($_POST['subtitleItalic']) ? 1 : 0);
				$stmt->bindParam(':subtitleItalic', $_POST['subtitleItalic'], PDO::PARAM_INT);
				//$subtitleUnderline = (isset($_POST['subtitleUnderline']) ? 1 : 0);
				$stmt->bindParam(':subtitleUnderline', $_POST['subtitleUnderline'], PDO::PARAM_INT);
				$stmt->bindParam(':backColor', $_POST['backColor'], PDO::PARAM_STR);
				$stmt->bindParam(':borderColor', $_POST['borderColor'], PDO::PARAM_STR);
				$stmt->bindParam(':borderThickness', $_POST['borderThickness'], PDO::PARAM_INT);
				$stmt->bindParam(':borderRadius', $_POST['borderRadius'], PDO::PARAM_INT);
				$stmt->bindParam(':xAxisTitleText', $_POST['xAxisTitleText'], PDO::PARAM_STR);
				$stmt->bindParam(':xAxisTitleFontID', $_POST['xAxisTitleFontID'], PDO::PARAM_INT);
				$stmt->bindParam(':xAxisTitleColor', $_POST['xAxisTitleColor'], PDO::PARAM_STR);
				$stmt->bindParam(':xAxisTitleFontSize', $_POST['xAxisTitleFontSize'], PDO::PARAM_INT);
				//$xAxisTitleBold = (isset($_POST['xAxisTitleBold']) ? 1 : 0);
				$stmt->bindParam(':xAxisTitleBold', $_POST['xAxisTitleBold'], PDO::PARAM_INT);
				//$xAxisTitleItalic = (isset($_POST['xAxisTitleItalic']) ? 1 : 0);
				$stmt->bindParam(':xAxisTitleItalic', $_POST['xAxisTitleItalic'], PDO::PARAM_INT);
				//$xAxisTitleUnderline = (isset($_POST['xAxisTitleUnderline']) ? 1 : 0);
				$stmt->bindParam(':xAxisTitleUnderline', $_POST['xAxisTitleUnderline'], PDO::PARAM_INT);
				$xAxisLabelsEnabled = (isset($_POST['xAxisLabelsEnabled']) ? 1 : 0);
				$stmt->bindParam(':xAxisLabelsEnabled', $xAxisLabelsEnabled, PDO::PARAM_INT);
				$stmt->bindParam(':xAxisLabelsRotation', $_POST['xAxisLabelsRotation'], PDO::PARAM_INT);
				$stmt->bindParam(':xAxisLabelsFontID', $_POST['xAxisLabelsFontID'], PDO::PARAM_INT);
				$stmt->bindParam(':xAxisLabelsColor', $_POST['xAxisLabelsColor'], PDO::PARAM_STR);
				$stmt->bindParam(':xAxisLabelsFontSize', $_POST['xAxisLabelsFontSize'], PDO::PARAM_INT);
				//$xAxisLabelsBold = (isset($_POST['xAxisLabelsBold']) ? 1 : 0);
				$stmt->bindParam(':xAxisLabelsBold', $_POST['xAxisLabelsBold'], PDO::PARAM_INT);
				//$xAxisLabelsItalic = (isset($_POST['xAxisLabelsItalic']) ? 1 : 0);
				$stmt->bindParam(':xAxisLabelsItalic', $_POST['xAxisLabelsItalic'], PDO::PARAM_INT);
				//$xAxisLabelsUnderline = (isset($_POST['xAxisLabelsUnderline']) ? 1 : 0);
				$stmt->bindParam(':xAxisLabelsUnderline', $_POST['xAxisLabelsUnderline'], PDO::PARAM_INT);
				$LegendEnabled = (isset($_POST['LegendEnabled']) ? 1 : 0);
				$stmt->bindParam(':LegendEnabled', $LegendEnabled, PDO::PARAM_INT);
				$stmt->bindParam(':legendItemFontID', $_POST['legendItemFontID'], PDO::PARAM_INT);
				$stmt->bindParam(':legendItemFontSize', $_POST['legendItemFontSize'], PDO::PARAM_INT);
				$stmt->bindParam(':legendItemColor', $_POST['legendItemColor'], PDO::PARAM_STR);
				//$legendItemBold = (isset($_POST['legendItemBold']) ? 1 : 0);
				$stmt->bindParam(':legendItemBold', $_POST['legendItemBold'], PDO::PARAM_INT);
				//$legendItemItalic = (isset($_POST['legendItemItalic']) ? 1 : 0);
				$stmt->bindParam(':legendItemItalic', $_POST['legendItemItalic'], PDO::PARAM_INT);
				//$legendItemUnderline = (isset($_POST['legendItemUnderline']) ? 1 : 0);
				$stmt->bindParam(':legendItemUnderline', $_POST['legendItemUnderline'], PDO::PARAM_INT);
				$stmt->bindParam(':legendBackColor', $_POST['legendBackColor'], PDO::PARAM_STR);
				$stmt->bindParam(':legendBorderColor', $_POST['legendBorderColor'], PDO::PARAM_STR);
				$stmt->bindParam(':legendBorderThickness', $_POST['legendBorderThickness'], PDO::PARAM_INT);
				$stmt->bindParam(':legendBorderRadius', $_POST['legendBorderRadius'], PDO::PARAM_INT);
				$stmt->bindParam(':legendAlign', $_POST['legendAlign'], PDO::PARAM_STR);
				$stmt->bindParam(':legendVerticalAlign', $_POST['legendVerticalAlign'], PDO::PARAM_STR);
				$stmt->bindParam(':legendx', $_POST['legendx'], PDO::PARAM_INT);
				$stmt->bindParam(':legendy', $_POST['legendy'], PDO::PARAM_INT);
				$stmt->bindParam(':legendLayout', $_POST['legendLayout'], PDO::PARAM_STR);
				$legendShadow = (isset($_POST['legendShadow']) ? 1 : 0);
				$stmt->bindParam(':legendShadow', $legendShadow, PDO::PARAM_INT);
				$stmt->bindParam(':legendShadowColor', $_POST['legendShadowColor'], PDO::PARAM_STR);
				
				$stmt->bindParam(':legendTitle', $_POST['legendTitle'], PDO::PARAM_STR);
				$stmt->bindParam(':legendTitleFontID', $_POST['legendTitleFontID'], PDO::PARAM_INT);
				$stmt->bindParam(':legendTitleFontSize', $_POST['legendTitleFontSize'], PDO::PARAM_INT);
				$stmt->bindParam(':legendTitleFontColor', $_POST['legendTitleFontColor'], PDO::PARAM_STR);
				
				//$legendTitleBold = (isset($_POST['legendTitleBold']) ? 1 : 0);
				$stmt->bindParam(':legendTitleBold', $_POST['legendTitleBold'], PDO::PARAM_INT);
				//$legendTitleItalic = (isset($_POST['legendTitleItalic']) ? 1 : 0);
				$stmt->bindParam(':legendTitleItalic', $_POST['legendTitleItalic'], PDO::PARAM_INT);
				//$legendTitleUnderline = (isset($_POST['legendTitleUnderline']) ? 1 : 0);
				$stmt->bindParam(':legendTitleUnderline', $_POST['legendTitleUnderline'], PDO::PARAM_INT);
				
				$stmt->bindParam(':legendPosition', $_POST['legendPosition'], PDO::PARAM_STR);
				
				
				$stmt->execute();
				
				echo $stmt;
		}
	
	
	//End Added for Chart Options
	
	
		
		
		

	}
?>