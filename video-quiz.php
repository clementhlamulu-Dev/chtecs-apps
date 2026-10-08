<?php
$user_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$user_id) {
	die("Invalid induction link.");
}

require_once __DIR__ . "/includes/db-connect.php"; // assumes a mysqli $conn


?>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Company Name Results type for the year ended Day&nbsp;Month&nbsp;Year |
		<?php echo $pageTitle ?>
	</title>
	<?php include('includes/metadata.php'); ?>
	<?php include('includes/head.php'); ?>
	<link rel="stylesheet" href="css/app-styles.css">
	<link rel="stylesheet" href="css/animate.css">

	<script src="js/wow.min.js"></script>
	<script>
		new WOW().init();
	</script>

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
						<h1 class="switch-red">
							Induction Assessment
						</h1>
					</div>
				</div>
			</div>

			<div class="quiz-block">
				<div class="row">
					<div class="col-lg-12">
						<!-- <p><?php //echo $Quiz_Results ?></p> -->
					</div>
				</div>

				<form action="quiz-results.php?id=<?php echo $user_id; ?>" method="post">
					<div class="row">
						<div class="col-lg-12">
							<p class="error-message">
								<?php echo $q1Err ?>
								</php>
							<div class="question fadeIn" id="question1">
								<p>What is the main purpose of health and safety?</p>

								<input type="radio" id="Run" name="question1" value="To stay safe every day">
								<label for="" id="To stay safe every day.">To stay safe every day.</label><br>

								<input type="radio" id="stay" name="question1"
									value="To show that you can follow the rules.">
								<label for="" id="To show that you can follow the rules.">To show that you can
									follow
									the rules.</label><br>

								<input type="radio" id="call-sos" name="question1" value="To stay employed">
								<label for="" id="To stay employed">To stay employed</label><br>

								<input type="radio" id="emergency-exit" name="question1"
									value="Protect Employer's Equipment">
								<label for="" id="Protect Employer's Equipment">Protect Employer's
									Equipment</label><br>



								<div class="quiz-nav">

									<div class="next-block" onclick="nextQuestion()">&nbsp;</div>
								</div>
							</div>

							<div class="question fadeIn" id="question2">
								<p>One of your right as employee is to </p>
								<input type="radio" id="Run" name="question2" value="Run">
								<label for="" id="Run">Run</label><br>

								<input type="radio" id="stay" name="question2" value="To Report unsafe enviroment">
								<label for="" id="stay">To Report unsafe enviroment</label><br>

								<input type="radio" id="call-sos" name="question2" value="Report your duty early">
								<label for="" id="callSos">Report your duty early</label><br>

								<input type="radio" id="emergency-exit" name="question2"
									value="Work in a Health and Safe Environment">
								<label for="" id="emergency-exit">Work in a Health and Safe Environment</label><br>



								<div class="quiz-nav">
									<div class="prev-block" onclick="PrevQuestion()">&nbsp;</div>
									<div class="next-block" onclick="nextQuestion()">&nbsp;</div>
								</div>
							</div>

							<div class="question fadeIn" id="question3">
								<p>One of your responsibility is to </p>
								<input type="radio" id="Run" name="question3" value="Run">
								<label for="" id="Run">Run</label><br>

								<input type="radio" id="stay" name="question3"
									value="Follow health and Safety instructions">
								<label for="" id="stay">Follow health and Safety instructions</label><br>

								<input type="radio" id="call-sos" name="question3"
									value="Call supervisor whenever something goes wrong">
								<label for="" id="callSos">Call supervisor whenever something goes wrong</label><br>

								<input type="radio" id="emergency-exit" name="question3" value="None of the above">
								<label for="" id="emergency-exit">None of the above</label>



								<div class="quiz-nav">
									<div class="prev-block" onclick="PrevQuestion()">&nbsp;</div>
									<div class="next-block" onclick="nextQuestion()">&nbsp;</div>
								</div>
							</div>

							<div class="question fadeIn" id="question4">
								<p>Your responsibility as an employee is to </p>
								<input type="radio" id="Run" name="question4"
									value="Take care of your own health and safety">
								<label for="" id="Run">Take care of your own health and safety</label><br>

								<input type="radio" id="stay" name="question4" value="Stay indoors">
								<label for="" id="stay">Stay indoors</label><br>

								<input type="radio" id="call-sos" name="question4" value="Call SOS">
								<label for="" id="callSos">Call SOS</label><br>

								<input type="radio" id="emergency-exit" name="question4" value="All of the Above">
								<label for="" id="emergency-exit">All of the Above</label>



								<div class="quiz-nav">
									<div class="prev-block" onclick="PrevQuestion()">&nbsp;</div>
									<div class="next-block" onclick="nextQuestion()">&nbsp;</div>
								</div>
							</div>

							<div class="question fadeIn" id="question5">
								<p>What should you do if you notice unsafe event</p>
								<input type="radio" id="Run" name="question5" value="Run">
								<label for="" id="Run">Run</label><br>

								<input type="radio" id="stay" name="question5"
									value="Follow health and safety standards">
								<label for="" id="stay">Follow health and safety standards</label><br>

								<input type="radio" id="call-sos" name="question5" value="Speak up">
								<label for="" id="callSos">Speak up</label><br>

								<input type="radio" id="emergency-exit" name="question5" value="Knock off and go home">
								<label for="" id="emergency-exit">Knock off and go home</label>



								<div class="quiz-nav ">
									<div class="prev-block" onclick="PrevQuestion()">&nbsp;</div>

								</div>
								<div class="submit-btn-block quiz-nav">
									<button class="submit-btn" type="submit">Finish and Submit Quiz</button>
								</div>
							</div>
							<style>
								.quiz-nav {

									display: flex;
									gap: 30px;
									justify-content: center;
								}


								.prev-block,
								.next-block {
									display: inline-block;
									position: relative;
									padding: 15px;
									width: 40px;
									height: 40px;
									border: 1px solid #1B3B5F;
									border-radius: 50%;
									cursor: pointer;
									transition: 400ms all ease-in-out;
								}

								.prev-block:hover,
								.next-block:hover {

									box-shadow: 0px 0px 11px 0px #1B3B5F;


								}

								.question p {
									font-weight: 600;
								}

								.prev-block:before {
									content: "\e93b";
									font-family: "icomoon";
									position: absolute;
									transition: inherit;

									color: #1B3B5F;
									font-size: 15px;
									font-weight: 600;

									top: 50%;
									transform: translate(-50%, -50%);
									left: 50%;
								}

								.next-block:before {
									content: "\e93c";
									font-family: "icomoon";
									position: absolute;
									transition: inherit;

									color: #1B3B5F;
									font-size: 15px;
									font-weight: 600;

									top: 50%;
									transform: translate(-50%, -50%);
									left: 50%;
								}
							</style>
							<script>
								let current = 1;
								const totalQuestions = 5;

								function nextQuestion() {
									document.getElementById("question" + current).style.display = "none";
									current++;

									if (current <= totalQuestions) {
										document.getElementById("question" + current).style.display = "block";
									}
								}

								function PrevQuestion() {
									document.getElementById("question" + current).style.display = "none";
									current--;

									if (current <= totalQuestions) {
										document.getElementById("question" + current).style.display = "block";
									}
								}

							</script>
						</div>
					</div>

				</form>

			</div>
		</div>
	</div>


	<?php include('includes/footer.php'); ?>
</body>

</html>