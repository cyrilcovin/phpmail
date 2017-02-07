<?php
if(isset($_GET['id'])){
    $id=$_GET['id'];
    $sql = 'UPDATE liste set valide = 0 * from liste where id=' . $id;
    mysqli_query($connexion_utilisateur, $sql) or die(mysqli_error($connexion_utilisateur)." dans la requête <br>$sql");
}
require 'PHPMailer/PHPMailerAutoload.php';

$connexion_utilisateur= mysqli_connect("127.0.0.1", "root", "", "phpmail");

$sql = "select * from user where user_name = 'cyril';";
$rs_user = mysqli_query($connexion_utilisateur, $sql) or die(mysqli_error($connexion_utilisateur));
if (mysqli_num_rows($rs_user))
{
    $user = mysqli_fetch_assoc($rs_user);
}

$sql = "select * from liste where valide=1";
$rs_liste = mysqli_query($connexion_utilisateur, $sql) or die(mysqli_error($connexion_utilisateur));
while($liste=mysqli_fetch_assoc($rs_liste)){
    usleep(1000);

    date_default_timezone_set('Etc/UTC');

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
    $mail->Username = $user["user_mail"];

    $mail->Password = $user["user_pwd"];

    //Password to use for SMTP authentication

    //Set who the message is to be sent from
    $mail->setFrom('cyril.covin@gmail.com', $liste['nom'].' '.$liste['prenom']);

    //Set an alternative reply-to address
    $mail->addReplyTo($liste['email'], $liste['nom'].' '.$liste['prenom']);

    //Set who the message is to be sent to
    $mail->addAddress($liste['email'], $liste['nom'].' '.$liste['prenom']);

    //Set the subject line
    $mail->Subject = 'PHPMailer GMail SMTP test';

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML('bonjour: '.$liste['prenom'].' '.$liste['nom'].'<br>Pour vous desabonnez cliquez ici: <a href="http://localhost/phpmail/index.php?id='.$liste['id'].'">desabonne</a>');

    //Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';

    //Attach an image file
    $mail->addAttachment('phpmail.rar');

    //send the message, check for errors
    if($liste['valide']==1) {
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            $liste['erreur'] = $liste['erreur'] + 1;
            $sql = 'UPDATE liste set erreur = ' . $liste['erreur'] . ' where id=' . $liste['id'];
            mysqli_query($connexion_utilisateur, $sql) or die(mysqli_error($connexion_utilisateur)." dans la requête <br>$sql");
            if ($liste['erreur'] > 9) {
                $sql = 'UPDATE liste set valide = 0 where id=' . $liste['id'];
                mysqli_query($connexion_utilisateur, $sql) or die(mysqli_error($connexion_utilisateur)." dans la requête <br>$sql");
            }
        } else {
            echo "Message sent!";
            $liste['erreur'] = 0;
            $sql = 'UPDATE liste set erreur = ' . $liste['erreur'] . ' where id=' . $liste['id'];
            mysqli_query($connexion_utilisateur, $sql) or die(mysqli_error($connexion_utilisateur)." dans la requête <br>$sql");
        }
    }
}

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that

