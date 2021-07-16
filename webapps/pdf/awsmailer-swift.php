<?php




echo 'trying';


// Replace sender@example.com with your "From" address. 
// This address must be verified with Amazon SES.
define('SENDER', 'noreply@theroishop.com');        

// Replace recipient@example.com with a "To" address. If your account 
// is still in the sandbox, this address must be verified.
define('RECIPIENT', 'chrisrudd.home@gmail.com');  
                                                      
// Replace smtp_username with your Amazon SES SMTP user name.
define('USERNAME','AKIAISQO5XSLJY27SNEQ');  

// Replace smtp_password with your Amazon SES SMTP password.
define('PASSWORD','AvRJAmKge6toMJSiS+JyY68Q7VlzLq8L/8BXi/mk2vgn');  

// If you're using Amazon SES in a region other than US West (Oregon), 
// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP  
// endpoint in the appropriate region.
define('HOST', 'email-smtp.us-east-1.amazonaws.com');  

 // The port you will connect to on the Amazon SES SMTP endpoint.
define('PORT', '587');     

// Other message information                                               
define('SUBJECT','Amazon SES test (SMTP interface accessed using PHP)');
define('BODY','This email was sent through the Amazon SES SMTP interface by using PHP.');


//*****************************************
//require '/usr/share/pear/Swift/swift_required.php';
echo 'trying';
require '../php/swiftmailer/lib/swift_required.php';

//require_once $_SERVER.'lib/swift_required.php';
 
  //Create the Transport
  $transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
  
  //$transport = new Swift_AWSTransport(
  //  'AKIAICG5ICQLKIVLCTZA',
  //  'AnNgQ9fAeMuWNM7GVFMkw5rcWWg9P+RiRpr4XHSbMori'
  //);
echo 'trying';
  //Create the Mailer using your created Transport
  $mailer = Swift_Mailer::newInstance($transport);
 
  //Create the message
  $message = Swift_Message::newInstance()
  ->setSubject("Email Test")
  ->setFrom(array('noreply@theroishop.com'))
  ->setTo(array('crudd@theroishop.com'))
  ->setBody("
<p>
  Test using SwiftMailer Via AWS.
</p>
  ", 'text/html');
 
  $mailer->send( $message );

echo 'Email Sent With SwiftMailer!';
//*********************************************

?>