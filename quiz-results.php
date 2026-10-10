<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$user_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$user_id) {
	die("Invalid induction link.");
}

require_once __DIR__ . "/includes/db-connect.php"; // assumes a mysqli $conn

$sql = "SELECT * FROM InductionTable WHERE user_id = $user_id";


$result = $conn->query($sql);
// Process the result set
if ($result->num_rows > 0) {
	// Output data of each row
	while ($row = $result->fetch_assoc()) {
		$VisitorEmail = $row["email"];
		$FullName = $row["firstname"];

		$userCore = $row["Quiz_Results"];
		$savedRow = $row;
	}
}



$Question1 = $Question3 = $Question2 = $Question4 = $Question5 = "";
$q1Err = $Quiz_Results = "";
$emailWarning = $saveError = "";
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
			$Quiz_Results = "Congratulations! You have passed the quiz. Your score is " . $userCore;
		} else {
			$Quiz_Results = "Sorry, you did not pass the quiz. Your score is " . $userCore;
		}

		$sql = "UPDATE InductionTable SET Question1 = '$Question1', Question2 = '$Question2', Question3 = '$Question3', Question4 = '$Question4', Question5 = '$Question5', Quiz_Results = '$userCore' WHERE user_id = $user_id";

		if ($conn->query($sql) === TRUE) {
			// Record updated successfully

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
											<a href='https://dev-apps.chtecs.co.za/visitor-page.php?id=$user_id'>
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
										https://dev-apps.chtecs.co.za/visitor-page.php?id=$user_id
									";

				$mail->send();

				//

			} catch (Exception $e) {

				error_log("Results email to $VisitorEmail failed: {$mail->ErrorInfo}");
				$emailWarning = "Your results have been saved, but we could not email you a copy. You can print this page or bookmark it instead.";

			}




			// ================================================================================================






			// ===========================Email to admin=================================================================


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
										https://dev-apps.chtecs.co.za/visitor-page.php?id=$user_id
									";

				$mail->send();

				//

			} catch (Exception $e) {

				error_log("Admin results notification for user $user_id failed: {$mail->ErrorInfo}");

			}




			// ================================================================================================
		} else {
			error_log("Quiz results update for user $user_id failed: " . $conn->error);
			$saveError = "We could not save your quiz answers. Please try again, or contact your host if the problem continues.";
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

// Viewing results later (email link / dashboard): show the answers saved in the database.
if ($_SERVER["REQUEST_METHOD"] !== "POST" && !empty($savedRow)) {
	$Question1 = $savedRow["Question1"] ?? "";
	$Question2 = $savedRow["Question2"] ?? "";
	$Question3 = $savedRow["Question3"] ?? "";
	$Question4 = $savedRow["Question4"] ?? "";
	$Question5 = $savedRow["Question5"] ?? "";
	$userCore = (int) ($savedRow["Quiz_Results"] ?? 0);
	if ($Question1 !== "") {
		$Quiz_Results = ($userCore >= 20 ? "Congratulations! You have passed the quiz." : "Sorry, you did not pass the quiz.") . " Your score is " . $userCore;
	}
}

$pageTitle = "Quiz Results";
$isVisitorPage = true;
$hasAttempt = !empty($Question1);
$passed = $hasAttempt && $userCore >= 20;
$scorePct = $totalScore > 0 ? round(($userCore / $totalScore) * 100) : 0;

$review = [
	["What is the main purpose of health and safety?", $Question1, "To stay safe every day"],
	["One of your rights as an employee is to:", $Question2, "Work in a Health and Safe Environment"],
	["One of your responsibilities is to:", $Question3, "Follow health and Safety instructions"],
	["Your responsibility as an employee is to:", $Question4, "Take care of your own health and safety"],
	["What should you do if you notice an unsafe event?", $Question5, "Speak up"],
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $pageTitle ?> | CH Tecs Visitor Induction</title>
	<?php include('includes/metadata.php'); ?>
	<?php include('includes/head.php'); ?>
</head>


<body id="quiz-results">
	<?php include('includes/header.php'); ?>
	<?php include('includes/navigation.php'); ?>
	<?php include('includes/report-tools.php'); ?>
	<div id="selectable-content">
		<div class="container">
			<div class="app-card-narrow">

				<ol class="steps" aria-label="Induction steps">
					<li class="done"><span class="step-num">&#10003;</span> Watch video</li>
					<li class="done"><span class="step-num">&#10003;</span> Assessment</li>
					<li class="current"><span class="step-num">3</span> Results</li>
				</ol>

				<?php if ($saveError): ?>
					<div class="app-alert app-alert-error" role="alert">
						<span aria-hidden="true">!</span><span><?php echo $saveError ?></span>
					</div>
				<?php endif; ?>
				<?php if ($emailWarning): ?>
					<div class="app-alert app-alert-warning" role="status">
						<span aria-hidden="true">!</span><span><?php echo $emailWarning ?></span>
					</div>
				<?php endif; ?>

				<?php if (!$hasAttempt): ?>
					<div class="app-card">
						<h1 class="switch-dblue">Quiz results</h1>
						<p class="page-intro">No assessment has been submitted for this induction yet.</p>
						<div class="form-actions">
							<a class="submit-btn" href="visitor-page.php?id=<?php echo $user_id; ?>">Start Induction</a>
						</div>
					</div>
				<?php else: ?>
					<div class="result-hero <?php echo $passed ? 'is-pass' : 'is-fail'; ?>">
						<div class="score-ring" style="--pct: <?php echo $scorePct; ?>;">
							<div class="score-value"><div><?php echo $userCore ?><small class="score-total">/ <?php echo $totalScore ?></small></div></div>
						</div>
						<div>
							<span class="badge <?php echo $passed ? 'badge-pass' : 'badge-fail'; ?>">
								<?php echo $passed ? 'PASSED' : 'NOT PASSED'; ?>
							</span>
							<h1 class="mt-2">Quiz results</h1>
							<p><?php echo htmlspecialchars($Quiz_Results) ?></p>
							<?php if (!$passed): ?>
								<p>You need 20 out of <?php echo $totalScore ?> to pass. Review the answers below and try again.</p>
							<?php endif; ?>
						</div>
					</div>

					<div class="app-card">
						<h2>Your answers</h2>
						<ol class="answer-list">
							<?php foreach ($review as $i => [$question, $answer, $correct]):
								$isCorrect = ($answer === $correct); ?>
								<li class="answer-item <?php echo $isCorrect ? 'is-correct' : 'is-wrong'; ?>">
									<h4><?php echo ($i + 1) . '. ' . htmlspecialchars($question); ?>
										<span class="badge <?php echo $isCorrect ? 'badge-pass' : 'badge-fail'; ?>">
											<?php echo $isCorrect ? 'Correct' : 'Incorrect'; ?>
										</span>
									</h4>
									<p><span class="label">Your answer:</span> <span class="yours"><?php echo htmlspecialchars($answer ?? ''); ?></span></p>
									<?php if (!$isCorrect): ?>
										<p><span class="label">Correct answer:</span> <?php echo htmlspecialchars($correct); ?></p>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ol>

						<div class="form-actions">
							<?php if (!$passed): ?>
								<a class="submit-btn" href="visitor-page.php?id=<?php echo $user_id; ?>">Retake Induction</a>
							<?php endif; ?>
							<button type="button" class="submit-btn btn-outline" onclick="printPage()">Print results</button>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>
