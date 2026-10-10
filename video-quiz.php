<?php
$user_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$user_id) {
	die("Invalid induction link.");
}

require_once __DIR__ . "/includes/db-connect.php"; // assumes a mysqli $conn

// Error passed back from quiz-results.php when not all questions were answered
$q1Err = htmlspecialchars($_GET['q1Err'] ?? '');
$pageTitle = "Induction Assessment";
$isVisitorPage = true;

// Question text and option values. Values must match the answers checked in quiz-results.php.
$questions = [
	1 => [
		'text' => 'What is the main purpose of health and safety?',
		'options' => [
			'To stay safe every day' => 'To stay safe every day.',
			'To show that you can follow the rules.' => 'To show that you can follow the rules.',
			'To stay employed' => 'To stay employed',
			"Protect Employer's Equipment" => "Protect Employer's Equipment",
		],
	],
	2 => [
		'text' => 'One of your rights as an employee is to:',
		'options' => [
			'Run' => 'Run',
			'To Report unsafe enviroment' => 'To report an unsafe environment',
			'Report your duty early' => 'Report your duty early',
			'Work in a Health and Safe Environment' => 'Work in a healthy and safe environment',
		],
	],
	3 => [
		'text' => 'One of your responsibilities is to:',
		'options' => [
			'Run' => 'Run',
			'Follow health and Safety instructions' => 'Follow health and safety instructions',
			'Call supervisor whenever something goes wrong' => 'Call your supervisor whenever something goes wrong',
			'None of the above' => 'None of the above',
		],
	],
	4 => [
		'text' => 'Your responsibility as an employee is to:',
		'options' => [
			'Take care of your own health and safety' => 'Take care of your own health and safety',
			'Stay indoors' => 'Stay indoors',
			'Call SOS' => 'Call SOS',
			'All of the Above' => 'All of the above',
		],
	],
	5 => [
		'text' => 'What should you do if you notice an unsafe event?',
		'options' => [
			'Run' => 'Run',
			'Follow health and safety standards' => 'Follow health and safety standards',
			'Speak up' => 'Speak up',
			'Knock off and go home' => 'Knock off and go home',
		],
	],
];
$totalQuestions = count($questions);
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


<body id="video-quiz">
	<?php include('includes/header.php'); ?>
	<?php include('includes/navigation.php'); ?>
	<div id="selectable-content">
		<div class="container">
			<div class="app-card-narrow">

				<ol class="steps" aria-label="Induction steps">
					<li class="done"><span class="step-num">&#10003;</span> Watch video</li>
					<li class="current"><span class="step-num">2</span> Assessment</li>
					<li><span class="step-num">3</span> Results</li>
				</ol>

				<div class="page-head">
					<h1 class="switch-dblue">Induction Assessment</h1>
					<p class="page-intro">Answer all <?php echo $totalQuestions; ?> questions. You need
						20 out of 25 to pass.</p>
				</div>

				<?php if ($q1Err): ?>
					<div class="app-alert app-alert-error" role="alert">
						<span aria-hidden="true">!</span><span><?php echo $q1Err ?></span>
					</div>
				<?php endif; ?>

				<div class="quiz-progress">
					<div class="meter-label">
						<span id="quizStepLabel">Question 1 of <?php echo $totalQuestions; ?></span>
						<span id="quizStepPct">20%</span>
					</div>
					<div class="progress-container" style="max-width:none">
						<div class="progress-bar" id="quizBar" style="width: <?php echo round(100 / $totalQuestions); ?>%;"></div>
					</div>
				</div>

				<form action="quiz-results.php?id=<?php echo $user_id; ?>" method="post" id="quizForm">
					<?php foreach ($questions as $num => $q): ?>
						<fieldset class="question" id="question<?php echo $num; ?>">
							<legend class="question-text"><?php echo $num . '. ' . htmlspecialchars($q['text']); ?></legend>

							<?php $opt = 0;
							foreach ($q['options'] as $value => $label):
								$opt++;
								$inputId = "q{$num}-opt{$opt}"; ?>
								<label class="option" for="<?php echo $inputId; ?>">
									<input type="radio" id="<?php echo $inputId; ?>" name="question<?php echo $num; ?>"
										value="<?php echo htmlspecialchars($value, ENT_QUOTES); ?>">
									<span><?php echo htmlspecialchars($label); ?></span>
								</label>
							<?php endforeach; ?>

							<p class="quiz-hint" aria-live="polite"></p>

							<div class="quiz-nav">
								<?php if ($num > 1): ?>
									<button type="button" class="submit-btn btn-outline" onclick="PrevQuestion()">&larr; Back</button>
								<?php else: ?>
									<span class="spacer"></span>
								<?php endif; ?>

								<?php if ($num < $totalQuestions): ?>
									<button type="button" class="submit-btn" onclick="nextQuestion()">Next &rarr;</button>
								<?php else: ?>
									<button class="submit-btn" type="submit">Finish and Submit Quiz</button>
								<?php endif; ?>
							</div>
						</fieldset>
					<?php endforeach; ?>
				</form>

				<script>
					let current = 1;
					const totalQuestions = <?php echo $totalQuestions; ?>;

					function isAnswered(n) {
						return !!document.querySelector('input[name="question' + n + '"]:checked');
					}

					function showQuestion(n) {
						for (let i = 1; i <= totalQuestions; i++) {
							document.getElementById("question" + i).style.display = (i === n) ? "block" : "none";
						}
						const pct = Math.round((n / totalQuestions) * 100);
						document.getElementById("quizStepLabel").textContent = "Question " + n + " of " + totalQuestions;
						document.getElementById("quizStepPct").textContent = pct + "%";
						document.getElementById("quizBar").style.width = pct + "%";
						const firstInput = document.querySelector("#question" + n + " input");
						if (firstInput) firstInput.focus({ preventScroll: true });
					}

					function setHint(n, msg) {
						document.querySelector("#question" + n + " .quiz-hint").textContent = msg;
					}

					function nextQuestion() {
						if (!isAnswered(current)) {
							setHint(current, "Please choose an answer to continue.");
							return;
						}
						setHint(current, "");
						if (current < totalQuestions) {
							current++;
							showQuestion(current);
						}
					}

					function PrevQuestion() {
						if (current > 1) {
							current--;
							showQuestion(current);
						}
					}

					// Clear the hint as soon as an answer is picked
					document.getElementById("quizForm").addEventListener("change", function (e) {
						const fs = e.target.closest(".question");
						if (fs) fs.querySelector(".quiz-hint").textContent = "";
					});

					// Jump to the first unanswered question instead of submitting an incomplete quiz
					document.getElementById("quizForm").addEventListener("submit", function (e) {
						for (let i = 1; i <= totalQuestions; i++) {
							if (!isAnswered(i)) {
								e.preventDefault();
								current = i;
								showQuestion(i);
								setHint(i, "Please answer this question before submitting.");
								return;
							}
						}
					});
				</script>
			</div>
		</div>
	</div>


	<?php include('includes/footer.php'); ?>
</body>

</html>
