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
				.das-summery {
					background-color: #1B3B5F;
					color: #fff;
					padding: 20px;
					margin-bottom: 20px;
					min-height: 100%;
					border-radius: 5px;
					text-align: center;
				}

				.das-discription {
					font-weight: 400;
					font-size: 13px;
				}

				.dash-number {
					font-size: 30px;
					font-weight: 600;
					text-align: center;
				}
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
																<p class='user-email'>Video Progress: $Videoprogress_percent%</p>
																	<div class='progress-container'>
											
																		<div class='progress-bar'
																			style='width: $Videoprogress_percent%;'> 
																		</div>

																	</div>
																</div>
																	
																	<p class='user-email'>Quiz Score: $Quiz_score</p>
																	<p class='user-email'>Quiz Results: $Quiz_Results</p>
																</div>
														</div>
													</div>
												</div>

												<div class='col-lg-1'>
													<div class='min-100-relative'>
															<div class='video-block'>
															<div class='dblock'>
																<a href='quiz-results.php?id=$user_id' class='btn delete-btn'>Delete</a>
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
					.dblock {
						display: block;
						margin-bottom: 15px;
					}

					.progress-container {
						width: 100px;
						height: 15px;
						background-color: #f3f3f3;
						border-bottom-left-radius: 30px;
						border-bottom-right-radius: 30px;
						border-top-left-radius: 30px;
						border-top-right-radius: 30px;
						border: 1px solid #1B3B5F;

					}

					.progress-bar {
						height: 100%;
						background-color: #1B3B5F;
						border-bottom-left-radius: 30px;
						border-bottom-right-radius: 30px;
						border-top-left-radius: 30px;
						border-top-right-radius: 30px;
					}

					.dashboard-headline {
						font-size: 22px;
						margin-bottom: 0px;
						font-weight: 600;
						color: #1B3B5F;
					}

					.video-block {
						height: 100%;
						padding: 0px 0px;
						padding-top: 0px;
					}

					.name-block {
						position: relative;
						padding-left: 70px;
					}

					.name-block h3 {
						font-size: 22px;
						margin-bottom: 5px;
						margin-top: 0px !important;
					}

					.user-email {
						font-size: 14px;
						color: #1B3B5F;
						margin-bottom: 0px;
						font-style: italic;
					}

					.dashboard-block {
						border-bottom: 1px solid #ccc;
						padding: 10px 0;
					}

					.min-100-relative {
						position: relative;
						min-height: 100%;
					}

					.initials-blcok {
						position: absolute;
						top: 33%;
						left: 23px;
						transform: translate(-50%, -50%);
					}

					.initials {
						display: inline-block;
						width: 50px;
						height: 50px;
						border-radius: 50%;
						background-color: #eee;
						color: #1B3B5F;
						border: 1px solid #1B3B5F;
						text-align: center;
						line-height: 50px;
						font-size: 20px;
					}
				</style>


			</div>
		</div>
	</div>
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>