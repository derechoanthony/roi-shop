<?php
		
	//CONFIGURATION for SmartAdmin UI

	//ribbon breadcrumbs config
	//array("Display Name" => "URL");
	$breadcrumbs = array(
		"Home" => APP_URL
	);
		
	/*navigation array config

	ex:
	"dashboard" => array(
		"title" => "Display Title",
		"url" => "http://yoururl.com",
		"icon" => "fa-home"
		"label_htm" => "<span>Add your custom label/badge html here</span>",
		"sub" => array() //contains array of sub items with the same format as the parent
	)

	*/
	$page_nav = array(
		"dashboard" => array(
			"title" => "Dashboard (In Progress)",
			"url" => "#",
			"icon" => "fa-home"
		),
		"users" => array(
			"title" => "Users (Beta)",
			"url" => "ajax/users.php",
			"icon" => "fa-user",
			"label_htm" => '<span id="userCount" class="badge pull-right inbox-badge">'.count($getUsers).'</span>'
		),
		"structure" => array(
			"title" => "Structure (In Progress)",
			"icon" => "fa-gears",
			"sub" => array(
				"sections" => array(
					"title" => "Sections",
					"url" => "ajax/sections.php"
				),
				"entries" => array(
					"title" => "Entries",
					"url" => "ajax/entries.php"
				),
				"pdf" => array(
					"title" => "PDF",
					"url" => "#"
				)
			)
		)
	);

	//configuration variables
	$page_title = "";
	$page_css = array();
	$no_main_header = false; //set true for lock.php and login.php
	$page_body_prop = array(); //optional properties for <body>
	
?>