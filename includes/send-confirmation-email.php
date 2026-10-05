<?php
require_once __DIR__ . "../../environment-sec/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'mail.chtecs.co.za';
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@chtecs.co.za';
    $mail->Password = '@Clementhlamulu20';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Sender
    $mail->setFrom(
        'no-reply@chtecs.co.za',
        'CH Technological Solutions'
    );

    // Recipient
    $mail->addAddress($VisitorEmail, $FullName);

    // BCC
    $mail->addBCC('clementhlamulu@gmail.com');

    // Email format
    $mail->isHTML(true);

    $mail->Subject = 'Visitor Induction Required';

    $mail->Body = "
        <html>
        <body>

        <p>Hi $FullName,</p>

        <p>
            You have been invited to complete the visitor induction
            for $businessUnit.
        </p>

        <p>
            Please click the link below to watch the induction video
            and complete the short assessment.
        </p>

        <p>
            <a href='https://dev-apps.chtecs.co.za/visitor-page.php?id=$user_id&name=$VisitorName&businessUnit=$businessUnit&host=$Host'>
                Start Visitor Induction
            </a>
        </p>

        <p>Thank you.</p>

        </body>
        </html>
    ";

    $mail->AltBody = "
        Hi $FullName,

        You have been invited to complete the visitor induction
        for $businessUnit.

        Please visit the induction page to complete the video
        and assessment.
    ";

    $mail->send();

    echo "Email sent successfully.";

} catch (Exception $e) {

    echo "Email could not be sent. Error: {$mail->ErrorInfo}";

}

?>