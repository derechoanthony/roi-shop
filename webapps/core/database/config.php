<?php

//configure constants

$directory = realpath(dirname(__FILE__));
$document_root = realpath($_SERVER['DOCUMENT_ROOT']);
$base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .
    $_SERVER['HTTP_HOST'];
if(strpos($directory, $document_root)===0) {
    $base_url .= str_replace(DIRECTORY_SEPARATOR, '/', substr($directory, strlen($document_root)));
}

defined("APP_URL") ? null : define("APP_URL", str_replace("/inc", "/assets", $base_url));
defined("APP_BASE") ? null : define("APP_BASE", str_replace("/inc", "", $base_url));
defined("COMPANY_FILES") ? null : define("COMPANY_FILES", str_replace("/inc", "/company_specific_files", $base_url));

//Assets URL, location of your css, img, js, etc. files
defined("ASSETS_URL") ? null : define("ASSETS_URL", APP_URL);


//require library files
//require_once("util.php");

?>