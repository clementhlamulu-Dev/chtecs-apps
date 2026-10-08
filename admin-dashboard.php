<?php
require_once __DIR__ . "/includes/db-connect.php";
?>

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
						<h1 class="switch-dblue">
							Admin Dashboard
						</h1>
					</div>
				</div>
			</div>

			<div class="row">
				<?php
				$sql = "SELECT COUNT(*) AS userCount FROM InductionTable WHERE user_id IS NOT NULL";
				// Execute the SQ
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					// Output data of each row
					while ($row = $result->fetch_assoc()) {
						$userCount = $row["userCount"];
					}
				}
				?>
				<div class="col-lg-2">
					<div class="das-summery">
						<div class="dash-number"><?php echo $userCount; ?></div>
						<div class="das-discription">Number of users in the system</div>
					</div>
				</div>

				<?php
				$sql = "SELECT COUNT(*) AS WatchVideoCount FROM InductionTable WHERE VideoProgress_seconds != 0";
				// Execute the SQ
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					// Output data of each row 
					while ($row = $result->fetch_assoc()) {
						$WatchVideoCount = $row["WatchVideoCount"];
					}
				}
				?>
				<div class="col-lg-2">
					<div class="das-summery">
						<div class="dash-number"><?php echo $WatchVideoCount; ?></div>
						<div class="das-discription">Total Number of users That Wached the Video</div>
					</div>
				</div>

				<?php
				$sql = "SELECT COUNT(*) AS QuizTakersCount FROM InductionTable WHERE Question1 IS NOT NULL";
				// Execute the SQL
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					// Output data of each row
					while ($row = $result->fetch_assoc()) {
						$QuizTakersCount = $row["QuizTakersCount"];
					}
				}
				?>
				<div class="col-lg-2">
					<div class="das-summery">
						<div class="dash-number"><?php echo $QuizTakersCount; ?></div>
						<div class="das-discription">Total Number of users that took the Quiz</div>
					</div>
				</div>

				<?php
				$sql = "SELECT COUNT(*) AS QuizPassersCount FROM InductionTable WHERE Quiz_Results >=20";
				// Execute the SQL
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					// Output data of each row
					while ($row = $result->fetch_assoc()) {
						$QuizPassersCount = $row["QuizPassersCount"];
					}
				}
				?>
				<div class="col-lg-2">
					<div class="das-summery">
						<div class="dash-number"><?php echo $QuizPassersCount; ?></div>
						<div class="das-discription">Total number of users that passed the quiz</div>
					</div>
				</div>

				<?php
				$sql = "SELECT COUNT(*) AS QuizFailersCount FROM InductionTable WHERE Question1 IS NOT NULL AND Quiz_Results < 20";
				// Execute the SQL
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					// Output data of each row
					while ($row = $result->fetch_assoc()) {
						$QuizFailersCount = $row["QuizFailersCount"];
					}
				}
				?>
				<div class="col-lg-2">
					<div class="das-summery">
						<div class="dash-number"><?php echo $QuizFailersCount; ?></div>
						<div class="das-discription">Total number of users that failed the quiz</div>
					</div>
				</div>

				<?php
				$sql = "SELECT COUNT(*) FROM InductionTable WHERE user_id IS NOT NULL";
				// Execute the SQ
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					// Output data of each row
					while ($row = $result->fetch_assoc()) {
						$userCount = $row["COUNT(*)"];
					}
				}
				?>
				<div class="col-lg-2">
					<div class="das-summery">
						<div class="dash-number"><?php echo $QuizFailersCount; ?></div>
						<div class="das-discription">Total number of users that failed the quiz</div>
					</div>
				</div>
			</div>

			<style>

			</style>


			<div class="row">
				<div class="dashboard-block">
					<div class="row">
						<div class="col-lg-3">
							<h2 class="dashboard-headline">User Details</h2>
						</div>


						<div class="col-lg-3">
							<h2 class="dashboard-headline">Business Department</h2>
						</div>

						<div class="col-lg-3">
							<h2 class="dashboard-headline">Induction Host</h2>
						</div>
						<div class="col-lg-3">
							<h2 class="dashboard-headline">Quiz Details</h2>
						</div>
					</div>
				</div>




				<?php
				$sql = "SELECT * FROM InductionTable";
				// Execute the SQL query
				$result = $conn->query($sql);

				// Process the result set
				if ($result->num_rows > 0) {
					// Output data of each row
					while ($row = $result->fetch_assoc()) {
						// echo "id: " . $row["id"] . " - Name: " . $row["firstname"] . " " . $row["lastname"] . "<br>";
						$user_id = $row["user_id"];

						$FullName = $row["firstname"];
						$VisitorEmail = $row["email"];
						$businessUnit = $row["BusinessUnit"];
						$Host = $row["Host"];
						$VideoProgress_seconds = $row["VideoProgress_seconds"];
						$Videoprogress_percent = $row["Videoprogress_percent"];
						//	$Question1 = $row[" Question1"];
						$Question2 = $row["Question2"];
						$Question3 = $row["Question3"];
						$Question4 = $row["Question4"];
						$Question5 = $row["Question5"];
						$Quiz_score = $row["Quiz_score"];
						$Quiz_Results = $row["Quiz_Results"];

						$initial = strtoupper(substr($FullName, 0, 1)); // Get the first letter of the name and convert to uppercase
				
						if ($Quiz_Results >= 20) {
							$QuizPassed = "Pass";
						} else {
							$QuizPassed = "Fail";
						}


						echo "
										<div class='dashboard-block'>
											<div class='row'>
													<div class='col-lg-3'>
														<div class='min-100-relative'>
															<div class='initials-blcok'>
																	<div class='initials'>$initial  </div>
															</div>
															<div class='name-block'>
																<h3 class='switch-dblue'>$FullName</h3>
																<p class='user-email'>$VisitorEmail</p>
																<p class='user-email'>user id: $user_id</p>
															</div>
														</div>
													</div>


													<div class='col-lg-3'>
															<div class='min-100-relative'>
																<div class='video-block'>
																	<p class='user-email'>$businessUnit</p>
																</div>
															</div>
													</div>


												<div class='col-lg-3'>
													<div class='min-100-relative'>
															<div class='video-block'>
																<p class='user-email'>$Host</p>
														</div>
													</div>
												</div>
												<div class='col-lg-2'>
													<div class='min-100-relative'>
															<div class='video-block'>
																<div class='row'>
																<div class='col-lg-12'>
																<p class='user-email'><strong>Video Progress:</strong> $Videoprogress_percent%</p>
																	<div class='progress-container'>
																		<div class='progress-bar'
																			style='width: $Videoprogress_percent%;'> 
																		</div>

																	</div>
																</div>
																	<p class='user-email'><strong>Quiz Results:</strong> $Quiz_Results</p>
																	<p class='user-email'><strong>Quiz Score:</strong> $QuizPassed</p>
																</div>
														</div>
													</div>
												</div>

												<div class='col-lg-1'>
													<div class='min-100-relative'>
															<div class='video-block'>
															<div class='dblock'>
															<form action='quiz-results.php' method='post'>
																<button type='submit' href='quiz-results.php?id=$user_id' class='btn delete-btn'>Delete</button>
																</form>
																</div>

																<div class='dblock'>
															
																<a href='quiz-results.php?id=$user_id' class='btn delete-btn'>View Results</a>
																
																</div>
														</div>
													</div>
												</div>
												
										

												
											</div>
										</div>
										";
					}
				} else {
					echo "0 results";
				}
				?>
				<style>

				</style>


			</div>
		</div>
	</div>
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>