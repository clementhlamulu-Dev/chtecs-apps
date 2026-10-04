<!DOCTYPE html>
<html lang="eng">

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

				<form action="">
					<div class="row">
						<div class="col-lg-12">

							<div class="question fadeIn" id="question1">
								<p>what should you do in an emergency?</p>
								<input type="radio" id="Run" name="question1" value="Run">
								<label for="" id="Run">Run</label><br>

								<input type="radio" id="stay" name="question1" value="Stay indoors">
								<label for="" id="stay">Stay indoors</label><br>

								<input type="radio" id="call-sos" name="question1" value="Call SOS">
								<label for="" id="callSos">Call SOS</label><br>

								<input type="radio" id="emergency-exit" name="question1" value="Emergency Exit">
								<label for="" id="emergency-exit">Emergency Exit</label><br>


								<div class="submit-btn-block">
									<a class="submit-btn" href="javascript::void()" onclick="nextQuestion()">Next
										Question</a>
								</div>
							</div>

							<div class="question fadeIn" id="question2">
								<p>Who should you do call in an emergency situation?</p>
								<input type="radio" id="Run" name="question1" value="Run">
								<label for="" id="Run">Run</label><br>

								<input type="radio" id="stay" name="question1" value="Stay indoors">
								<label for="" id="stay">Stay indoors</label><br>

								<input type="radio" id="call-sos" name="question1" value="Call SOS">
								<label for="" id="callSos">Call SOS</label><br>

								<input type="radio" id="emergency-exit" name="question1" value="Emergency Exit">
								<label for="" id="emergency-exit">Emergency Exit</label><br>


								<div class="submit-btn-block">
									<a class="submit-btn" href="javascript::void()" onclick="nextQuestion()">Next
										Question</a>
								</div>
							</div>

							<div class="question" id="question3">
								<p>what should you do when you are under pressure?</p>
								<input type="radio" id="Run" name="question1" value="Run">
								<label for="" id="Run">Run</label><br>

								<input type="radio" id="stay" name="question1" value="Stay indoors">
								<label for="" id="stay">Stay indoors</label><br>

								<input type="radio" id="call-sos" name="question1" value="Call SOS">
								<label for="" id="callSos">Call SOS</label><br>

								<input type="radio" id="emergency-exit" name="question1" value="Emergency Exit">
								<label for="" id="emergency-exit">Emergency Exit</label>


								<div class="submit-btn-block">
									<a class="submit-btn" href="javascript::void()" onclick="nextQuestion()">Next
										Question</a>
								</div>
							</div>

							<div class="question" id="question4">
								<p>what should you do when you are late</p>
								<input type="radio" id="Run" name="question1" value="Run">
								<label for="" id="Run">Run</label><br>

								<input type="radio" id="stay" name="question1" value="Stay indoors">
								<label for="" id="stay">Stay indoors</label><br>

								<input type="radio" id="call-sos" name="question1" value="Call SOS">
								<label for="" id="callSos">Call SOS</label><br>

								<input type="radio" id="emergency-exit" name="question1" value="Emergency Exit">
								<label for="" id="emergency-exit">Emergency Exit</label>


								<div class="submit-btn-block">
									<a class="submit-btn" href="javascript::void()" onclick="nextQuestion()">Next
										Question</a>
								</div>
							</div>

							<div class="question" id="question5">
								<p>what should you do whenever you will not report for duty in that particular day</p>
								<input type="radio" id="Run" name="question1" value="Run">
								<label for="" id="Run">Run</label><br>

								<input type="radio" id="stay" name="question1" value="Stay indoors">
								<label for="" id="stay">Stay indoors</label><br>

								<input type="radio" id="call-sos" name="question1" value="Call SOS">
								<label for="" id="callSos">Call SOS</label><br>

								<input type="radio" id="emergency-exit" name="question1" value="Emergency Exit">
								<label for="" id="emergency-exit">Emergency Exit</label>


								<div class="submit-btn-block">
									<button class="submit-btn">Finish and Submit Quiz</button>
								</div>
							</div>

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