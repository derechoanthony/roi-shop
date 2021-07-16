<?php

	$textKey = array_keys(array_column($_SESSION['textElements'], 'text_element_id'),$id);
	if($textKey){
		$text = $_SESSION['textElements'][$textKey[0]];
	}
	
	if($text['navigation']){
?>

<a class="smooth-scroll" href="<?= $text['navigation'] ?>">

<?php
	}

	if($text['formula']){
		
		$sql = "SELECT * FROM registered_functions
				WHERE function_id = :id
				LIMIT 1;";
				
		$stmt = $_SESSION['db']->prepare($sql);
		$stmt->bindParam(':id', $text['formula'], PDO::PARAM_INT);
		$stmt->execute();
		$formula = $stmt->fetch();		
	}
?>
	
	<?= $text['tag'] ? '<'. $text['tag'] . 
		( $text['formula'] ? ' data-format="'. $text['format'] .'"'. ' data-formula="'. $formula['function_formula'] .'"' : '' ) .
		( $text['class'] ? ' class="'. $text['class'] .'"' : '' ).
		'>' : '' ?>
		<?= $text['text'] ?>
	<?= $text['tag'] ? '</'. $text['tag'] .'>' : '' ?>

<?php

	if($text['navigation']){
?>

</a>

<?php
	}
?>