<?php

	if( $_POST['action'] == 'writeToFile' ) {

		$filename = 'json/' . $_POST['roi'] . '.json';

		//open or create the file
		$handle = fopen($filename,'w+');

		//write the data into the file
		fwrite($handle,$_POST['options']);

		//close the file
		fclose($handle);		
	}
	
	if( $_POST['action'] == 'resetTemplate' ) {
		
		$filename = 'json/' . $_POST['roi'] . '.json';
		
		unlink($filename);
	}
	
?>