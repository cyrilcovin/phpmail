<?php
$connexion_utilisateur= mysqli_connect("127.0.0.1", "root", "", "phpmail");
$sql = "select * from user where user_name = 'cyril';";
$rs_user = mysqli_query($connexion_utilisateur, $sql) or die(mysqli_error($connexion_utilisateur));
if (mysqli_num_rows($rs_user))
{
    $user = mysqli_fetch_assoc($rs_user);
    echo $user["user_id"];
    echo $user["user_name"];
    echo $user["user_mail"];
}

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require 'PHPMailer/PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "cyril.covin@gmail.com";

//Password to use for SMTP authentication

//Set who the message is to be sent from
$mail->setFrom('cyril.covin@gmail.com', 'First Last');

//Set an alternative reply-to address
$mail->addReplyTo('cyril.covin@outlook.fr', 'First Last');

//Set who the message is to be sent to
$mail->addAddress('cyril.covin@outlook.fr', 'John Doe');

//Set the subject line
$mail->Subject = 'PHPMailer GMail SMTP test';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('PHPMailer/examples/contents.html'), dirname(__FILE__));

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

//Attach an image file
$mail->addAttachment('PHPMailer/examples/images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
