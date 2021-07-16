<?php
	
	header("Content-type: text/css; charset: UTF-8");
	
	require_once( "../../../inc/config.php" );	
	require_once( "../../../inc/base.php" );	
	require_once( "../../../php/roi.styles.php" );

	$styles = new RoiStyles($db);
	$roitheme = $styles->retrieveRoiStyles();
	
	$primary_r = ( $roitheme[0]['primary_r'] ? $roitheme[0]['primary_r'] : 244 );
	$primary_g = ( $roitheme[0]['primary_g'] ? $roitheme[0]['primary_g'] : 115 );
	$primary_b = ( $roitheme[0]['primary_b'] ? $roitheme[0]['primary_b'] : 33 );

	$secondary_r = ( $roitheme[0]['secondary_r'] ? $roitheme[0]['secondary_r'] : 85 );
	$secondary_g = ( $roitheme[0]['secondary_g'] ? $roitheme[0]['secondary_g'] : 134 );
	$secondary_b = ( $roitheme[0]['secondary_b'] ? $roitheme[0]['secondary_b'] : 45 );
	
	/*$primary_r = 56;
	$primary_g = 124;
	$primary_b = 44;

	$secondary_r = 185;
	$secondary_g = 82;
	$secondary_b = 65;*/
	
	$max_primary = 255 / max( $primary_r, $primary_g, $primary_b );
	$max_secondary = 255 / max( $secondary_r, $secondary_g, $secondary_b );
	
	function getContrastYIQ( $r, $g, $b )
	{
		$yiq = (( $r * 299 )+( $g * 587 )+( $b * 114 ))/1000;
		return ($yiq >= 128) ? 'black' : 'white';
	}
	
?>

body {
	background: none repeat scroll 0 0 rgba( 255, 255, 255 );
	font-size: 14px;
}

textarea.form-control {
	font-size: 14px;
}

#header{
    background-color: rgb(243, 243, 244);
    display: block;
    height: 49px;
    margin: 0;
    padding: 0 13px 0 0;
    position: relative;
    z-index: 905;
}

aside ul img {
	padding: 10px;
	border-radius: 1px;
	<?= $roitheme[0]['logo-color'] == 'dark' ? 'background: #ddd' : '' ?>
}

.well, .panel {
	border: 1px solid rgba( <?= $primary_r ?>, <?= $primary_g ?>, <?= $primary_b ?>, .6 );
	background: none repeat scroll 0 0 rgba( 255, 255, 255, .6 );
}

.panel-heading, .panel-default > .panel-heading {
	background: rgba( <?= $primary_r ?>, <?= $primary_g ?>, <?= $primary_b ?>, .7 );
	bottom-border: 0px;
}

.panel-footer, .panel-default > .panel-footer {
	background: #ccc;
	border-top: 1px solid rgba( <?= $primary_r ?>, <?= $primary_g ?>, <?= $primary_b ?>, .6 );
}

.progress-bar, .slider-selection {
	background-color: rgba( <?= $secondary_r ?>, <?= $secondary_g ?>, <?= $secondary_b ?>, .55 );
}

.panel h3, h5.pod-attributes {
	color: <?= getContrastYIQ( $primary_r, $primary_r, $primary_r ) ?>;
	font-weight: 500;
}

h5.pod-attributes {
	font-size: 14px;
	margin-bottom: 5px;
}

.slider {, 
	margin-top: 0;
}

.panel-footer > .form-horizontal > .pod-attributes {
	margin-top: 13px;
}

.panel-footer > .form-horizontal > .form-group > .col-md-12 > .slider > .slider-track {
	border-color: rgba( <?= $primary_r ?>, <?= $primary_g ?>, <?= $primary_b ?>, .8 );
}

hr {
	border-color: rgba( <?= $primary_r ?>, <?= $primary_g ?>, <?= $primary_b ?>, .6 );
	margin-top: 8px;
	margin-bottom: 8px;
}

.slider-track {
	border: 1px solid rgba( <?= $primary_r ?>, <?= $primary_g ?>, <?= $primary_b ?>, .2 );
}

.btn-header > *:first-child > a {
	border: 1px solid rgba( <?= $primary_r ?>, <?= $primary_g ?>, <?= $primary_b ?>, .6 );
}

.btn-primary, .btn-primary:focus, btn-primary:active {
	background-color: rgb( <?= $secondary_r ?>, <?= $secondary_g ?>, <?= $secondary_b ?> );
	border-color: rgb( <?= floor( $secondary_r * .85 ) ?>, <?= floor( $secondary_g * .85 ) ?>, <?= floor( $secondary_b * .85 ) ?> );
}

.slider-handle {
    background: rgb( <?= 185 + floor( 45 * ( $primary_r / 255 ) ) ?>, <?= 185 + floor( 45 * ( $primary_g / 255 ) ) ?>, <?= 185 + floor( 45 * ( $primary_b / 255 ) ) ?> );
	border: 1px solid rgba( <?= $primary_r ?>, <?= $primary_g ?>, <?= $primary_b ?>, .6 );
	color: rgb( <?= 185 + floor( 45 * ( $primary_r / 255 ) ) ?>, <?= 185 + floor( 45 * ( $primary_g / 255 ) ) ?>, <?= 185 + floor( 45 * ( $primary_b / 255 ) ) ?> );
	text-shadow: 0 0 0 rgba(0,0,0);
}

.btn-primary:hover {
	background-color: rgb( <?= floor( $secondary_r * .85 ) ?>, <?= floor( $secondary_g * .85 ) ?>, <?= floor( $secondary_b * .85 ) ?> );
	border-color: rgb( <?= floor( $secondary_r * .85 ) ?>, <?= floor( $secondary_g * .85 ) ?>, <?= floor( $secondary_b * .85 ) ?> );
}

#project-context .label {
	visibility: hidden;
}

.form-control:focus {
	border-color: rgb( <?= floor( $secondary_r * .85 ) ?>, <?= floor( $secondary_g * .85 ) ?>, <?= floor( $secondary_b * .85 ) ?> );
}

.tooltip {
	color: blue;
}

.login-info > span {
	margin-left: 5px;
}

.custom-theme {
	border-radius: 5px; 
	border: 1px solid rgb( <?= $primary_r ?>, <?= $primary_g ?>, <?= $primary_b ?> );
	background: rgb( <?= 230 + floor( 25 * ( $primary_r / 255 ) ) ?>, <?= 230 + floor( 25 * ( $primary_g / 255 ) ) ?>, <?= 230 + floor( 25 * ( $primary_b / 255 ) ) ?> );
	color: #333;
}

.custom-theme .tooltipster-content {
	font-family: Arial, sans-serif;
	font-size: 14px;
	line-height: 16px;
	padding: 8px 10px;
}

.modal-content {
	background: rgb( <?= 230 + floor( 25 * ( $primary_r / 255 ) ) ?>, <?= 230 + floor( 25 * ( $primary_g / 255 ) ) ?>, <?= 230 + floor( 25 * ( $primary_b / 255 ) ) ?> );
}

.testimonials blockquote {
	margin: 0;
}

.page-title {
	font-size: 32px;
	margin-bottom:18px;
}

.textoFull p { font-size: 16px; }

#left-panel {
	z-index: 906;
}

.pace {
	display: none;
}

.tooltip {
	z-index: 1002;
}

.company-tooltip {
	border-radius: 5px; 
	border: 2px solid rgb( <?= $primary_r ?>, <?= $primary_g ?>, <?= $primary_b ?> );
	background: #eee;
	color: #666;
}

.company-tooltip .tooltipster-content {
	font-family: Arial, sans-serif;
	font-size: 14px;
	line-height: 16px;
	padding: 8px 10px;
}

#sparks { margin: 0}

#sparks li h5{ margin: 0 }