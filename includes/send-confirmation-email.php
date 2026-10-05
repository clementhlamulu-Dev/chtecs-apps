<?php

$to = $VisitorEmail;
$subject = "Visitor Induction Required";

$message = "
<html>
<head>
    <title>Visitor Induction</title>
</head>

<body>
    <p>Hi $FullName,</p>

    <p>
        You have been invited to complete the visitor induction for $businessUnit.
    </p>

    <p>
        Please click the link below to watch the induction video and complete the short assessment.
    </p>

    <p>
        <a href='https://yourwebsite.com/induction'>
            Complete Visitor Induction
        </a>
    </p>

    <p>Thank you.</p>
</body>
</html>
";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: CH technological solutions Website<no-reply@chtecs.co.za>\r\n";
$headers .= "Reply-To: $to\r\n";
$headers .= "Bcc: clementhlamulu@gmail.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent!";
} else {
    echo "Email failed.";
}

?>