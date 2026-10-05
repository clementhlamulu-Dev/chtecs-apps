<?php

$to = $VisitorEmail;
$subject = "Visitior Induction Required";

$message = "
<html>


<body>
    <p>Hi '', </p>

	<p>You hhave been invited to complete the visitor induction for '$businessUnit'. <p>
	<p>Please click below to watch the induction video and complete the short assesment. </p>

	<p></p>



</body>
</html>
";



$headers = [];
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: Website <website@example.com>\r\n";
$headers .= "From: Blu Label Website <no-reply@bluelabeltelecoms.co.za>";
$headers .= "Reply-To: " . $to;
$headers .= "Bcc: reginal.chauke@bastiongroup.co.za";




if (mail($to, $subject, $message, $headers)) {
    echo "Email sent!";
} else {
    echo "Email failed.";
}
?>