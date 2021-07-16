<?php

	header("Content-type: text/css; charset: UTF-8");

	require_once("../../common/new-base.php");
	require_once("../../common/config.php");
	
	require_once("../../php/classes/custom.styles.php");
	
	$roiStyles = new CalculatorBuilder($db);
	
	$roiMasterStyle = $roiStyles->retrieveRoiStyles();
	
	$navigationColor = $roiMasterStyle['navigation_color'];
	
?>

body {
  background-color: <?= $navigationColor ?>;
}