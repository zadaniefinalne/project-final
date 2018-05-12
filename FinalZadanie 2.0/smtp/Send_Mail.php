<?php
function Send_Mail($to,$subject,$body)
{
require 'class.phpmailer.php';
$from       = "zadaniefinalne@gmail.com";
$mail       = new PHPMailer();
$mail->IsSMTP(true);            // use SMTP
$mail->IsHTML(true);
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "tls://smtp.gmail.com"; // Amazon SES server, note "tls://" protocol
$mail->Port       =  465;                    // set the SMTP port
$mail->Username   = "zadaniefinalne@gmail.com";  // SMTP  username
$mail->Password   = "Tester123";  // SMTP password
$mail->SetFrom($from, 'From Me');
$mail->AddReplyTo($from,'From Me');
$mail->Subject    = $subject;
$mail->MsgHTML($body);
$address = $to;
$mail->AddAddress($address, $to);
$mail->Send();   
}
?>
