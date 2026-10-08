<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$user_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$user_id) {
	die("Invalid induction link.");
}

require_once __DIR__ . "/includes/db-connect.php"; // assumes a mysqli $conn
$Question1 = $Question3 = $Question2 = $Question4 = $Question5 = "";
$q1Err = $Quiz_Results = "";
$userCore = 0;
$totalScore = 25;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["question1"]) || empty($_POST["question2"]) || empty($_POST["question3"]) || empty($_POST["question4"]) || empty($_POST["question5"])) {
		$q1Err = "Please ensure that all the questions are answered";
		header("Location: video-quiz.php?id=$user_id&q1Err=" . urlencode($q1Err));
		exit();
	} else {
		$Question1 = $_POST["question1"];
		$Question2 = $_POST["question2"];
		$Question3 = $_POST["question3"];
		$Question4 = $_POST["question4"];
		$Question5 = $_POST["question5"];
		$userCore = checkAnswers($Question1, "To stay safe every day", $userCore);
		$userCore = checkAnswers($Question2, "Work in a Health and Safe Environment", $userCore);
		$userCore = checkAnswers($Question3, "Follow health and Safety instructions", $userCore);
		$userCore = checkAnswers($Question4, "Take care of your own health and safety", $userCore);
		$userCore = checkAnswers($Question5, "Speak up", $userCore);

		if ($userCore >= 20) {
			$Quiz_Results = "Congradulations!! You have passed the Quiz. Your score is " . $userCore;
		} else {
			$Quiz_Results = "Sorry You have failed your Quiz. Your score is " . $userCore;
		}

		$sql = "UPDATE InductionTable SET Question1 = '$Question1', Question2 = '$Question2', Question3 = '$Question3', Question4 = '$Question4', Question5 = '$Question5', Quiz_Results = '$userCore' WHERE user_id = $user_id";

		if ($conn->query($sql) === TRUE) {
			echo "Record updated successfully";

			// ===========================Email to user=================================================================

			require_once __DIR__ . '/../enviroment-sec/config.php';
			require __DIR__ . '/vendor/autoload.php';

			$mail = new PHPMailer(true);


			try {

				// SMTP configuration
				$mail->isSMTP();
				$mail->Host = 'mail.chtecs.co.za';
				$mail->SMTPAuth = true;
				$mail->Username = 'no-reply@chtecs.co.za';
				$mail->Password = $emailpass;
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
											$Quiz_Results. you have scored $userCore out of $totalScore. <br>
										</p>

										<p>
											Please View your results on the link below:<br>
											<a href='https://dev-apps.chtecs.co.za/quiz-results.php?id=$user_id'>
												View Quiz Results
											</a>
										</p>

										<p> or visit the induction page to complete the video and assessment again on:<br>
											<a href='https://dev-apps.chtecs.co.za/visitor-page.php?id=$last_id'>
												Start Visitor Induction
											</a>
										</p>

										<p>Thank you.</p>

										</body>
										</html>
									";

				$mail->AltBody = "
										Hi $FullName,

										$Quiz_Results;

										Please visit the induction page to complete the video
										and assessment again on:
										https://dev-apps.chtecs.co.za/visitor-page.php?id=$last_id
									";

				$mail->send();

				//

			} catch (Exception $e) {

				echo "Email could not be sent. Error: {$mail->ErrorInfo}";

			}




			// ================================================================================================






			// ===========================Email to user=================================================================


			require_once __DIR__ . '/../enviroment-sec/config.php';
			require __DIR__ . '/vendor/autoload.php';

			$mail = new PHPMailer(true);


			try {

				// SMTP configuration
				$mail->isSMTP();
				$mail->Host = 'mail.chtecs.co.za';
				$mail->SMTPAuth = true;
				$mail->Username = 'no-reply@chtecs.co.za';
				$mail->Password = $emailpass;
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
				$mail->Port = 465;

				// Sender
				$mail->setFrom(
					'no-reply@chtecs.co.za',
					'CH Technological Solutions'
				);

				// Recipient
				$mail->addAddress("clementhlamulu@gmail.com", "CH Tecs admin");

				// BCC
				$mail->addBCC('clementhlamulu@gmail.com');

				// Email format
				$mail->isHTML(true);

				$mail->Subject = $FullName . ' has completed the induction assessment';

				$mail->Body = "
										<html>
										<body>

										<p>Hi Admin,</p>

										<p>
											The visitor $FullName has completed the induction assessment and scored $userCore out of $totalScore. <br>

											
										</p>

										<p>
										View the results on the link below:<br>
											<a href='https://dev-apps.chtecs.co.za/admin-dashboard.php'>
												View Dashboard
											</a>
										</p>

										

										<p>Thank you.</p>

										</body>
										</html>
									";

				$mail->AltBody = "
										Hi $FullName,

										$Quiz_Results;

										Please visit the induction page to complete the video
										and assessment again on:
										https://dev-apps.chtecs.co.za/visitor-page.php?id=$last_id
									";

				$mail->send();

				//

			} catch (Exception $e) {

				echo "Email could not be sent. Error: {$mail->ErrorInfo}";

			}




			// ================================================================================================
		} else {
			echo "Error updating record: " . $conn->error;
		}
	}
}


function checkAnswers($ChoosenAnswer, $correctAnswer, $scoreCount)
{
	//this adds 5 points to thr scorecount variable if the answer is correct. if answer is wrong then the variable is left untouched
	if ($ChoosenAnswer === $correctAnswer) {
		$scoreCount = $scoreCount + 5;
	}
	return $scoreCount;
}

?>

<!DOCTYPE html>
<html lang="eng">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Quiz results
		<?php echo $pageTitle ?>
	</title>
	<?php include('includes/metadata.php'); ?>
	<?php include('includes/head.php'); ?>
</head>


<body id="<SECTIONSHORT>">
	<?php include('includes/header.php'); ?>
	<?php include('includes/navigation.php'); ?>
	<?php include('includes/report-tools.php'); ?>
	<?php include('includes/breadcrumb.php'); ?>
	<div id="selectable-content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="main-page-heading">
						<h1>
							Quiz results
						</h1>
					</div>
				</div>
			</div>


			<div class="row">
				<div class="col-lg-4">
					<div class="quiz-results-block">
						<h3></h3>

					</div>
				</div>
			</div>

			<div class="correction-block">
				<div class="r-question">
					<p>1. What is the main purpose of health and safety?</p>
					<p>Your answer: <?php echo $Question1 ?></p>
					<p>Correct answer: To stay safe every day</p>
				</div>
			</div>


			<div class="correction-block">
				<div class="r-question">
					<p>1. What is the main purpose of health and safety?</p>
					<p>Your answer: <?php echo $Question1 ?></p>
					<p>Correct answer: To stay safe every day</p>
				</div>
			</div>

			<div class="correction-block">
				<div class="r-question">
					<p>2. One of your right as employee is to?</p>
					<p>Your answer: <?php echo $Question2 ?></p>
					<p>Correct answer: Work in a Health and Safe Environment</p>
				</div>
			</div>


			<div class="correction-block">
				<div class="r-question">
					<p>3. What is the main purpose of health and safety?</p>
					<p>Your answer: <?php echo $Question3 ?></p>
					<p>Correct answer: Follow health and Safety instructions</p>
				</div>
			</div>

			<div class="correction-block">
				<div class="r-question">
					<p>4. Your responsibility as an employee is to?</p>
					<p>Your answer:
						<?php echo $Question4 ?>
					</p>
					<p>Correct answer: Take care of your own health and safety</p>
				</div>
			</div>


			<div class="correction-block">
				<div class="r-question">
					<p>5. What should you do if you notice unsafe event?</p>
					<p>Your answer:
						<?php echo $Question5 ?>
					</p>
					<p>Correct answer: Speak up</p>
				</div>
			</div>








		</div>
	</div>
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>