
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//require_once( "$root/webapps/php/numeral/Numeral.php" ); 									// Sets up connection to database


	require '../php/numeral/vendor/autoload.php';							//Required for emailing

use Stillat\Numeral\Languages\LanguageManager;
use Stillat\Numeral\Numeral;

// Create the language manager instance.
$languageManager = new LanguageManager;

// Create the Numeral instance.
$formatter = new Numeral;

// Now we need to tell our formatter about the language manager.
$formatter->setLanguageManager($languageManager);


echo ($formatter->format(1000.23, '+0,0'));


?>

